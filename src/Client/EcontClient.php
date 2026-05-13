<?php

declare(strict_types=1);

namespace Econt\EcontApi\Client;

use Econt\EcontApi\Configuration\EcontConfiguration;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Model\Response\EcontResponse;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Serializer\SerializerInterface;

class EcontClient implements EcontClientInterface
{
    private EcontConfiguration $configuration;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function __construct(
        EcontConfiguration $configuration,
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        ?LoggerInterface $logger = null
    ) {
        $this->configuration = $configuration;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->logger = $logger ?? new NullLogger();

        $psr17Factory = new Psr17Factory();
        $this->requestFactory = $psr17Factory;
        $this->streamFactory = $psr17Factory;
    }

    public static function create(
        EcontConfiguration $configuration,
        SerializerInterface $serializer,
        ?LoggerInterface $logger = null
    ): self {
        $httpClient = new Psr18Client();

        return new self($configuration, $httpClient, $serializer, $logger);
    }

    public function request(string $service, array $data = []): EcontResponse
    {
        $xmlData = $this->buildRequestXml($data);

        $request = $this->requestFactory->createRequest('POST', $this->configuration->getBaseUrl() . $service)
            ->withHeader('Content-Type', 'text/xml; charset=utf-8')
            ->withHeader('Accept', 'application/xml')
            ->withHeader('Authorization', $this->getBasicAuthHeader());

        $request->getBody()->write($xmlData);

        try {
            $response = $this->httpClient->sendRequest($request);
            $statusCode = $response->getStatusCode();
            $responseBody = (string) $response->getBody();

            if (empty($responseBody)) {
                return EcontResponse::error("Empty response body with status code: $statusCode");
            }

            return $this->parseXmlResponse($responseBody);
        } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
            throw new EcontNetworkException('Network error occurred: ' . $e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            throw new EcontApiException(null, null, 'Request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getConfiguration(): EcontConfiguration
    {
        return $this->configuration;
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    private function getBasicAuthHeader(): string
    {
        $credentials = $this->configuration->getUsername() . ':' . $this->configuration->getPassword();
        return 'Basic ' . base64_encode($credentials);
    }

    private function buildRequestXml(array $data): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request/>');

        $xml->addChild('language', $this->configuration->getLanguage());

        $this->arrayToXmlRecursive($xml, $data);

        return $xml->asXML();
    }

    private function arrayToXmlRecursive(\SimpleXMLElement $xml, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                continue;
            }

            if (is_array($value)) {
                $child = $xml->addChild($key);
                $this->arrayToXmlRecursive($child, $value);
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
            }
        }
    }

    private function camelCaseToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
    }

    private function parseXmlResponse(string $xmlString): EcontResponse
    {
        try {
            $xml = @simplexml_load_string($xmlString);

            if ($xml === false) {
                return EcontResponse::error('Invalid XML response received');
            }

            $isSuccess = isset($xml->success) && ((string) $xml->success === 'true' || (string) $xml->success === '1');
            $errorMessage = isset($xml->error) ? (string) $xml->error : null;
            $errorCode = isset($xml->error_code) ? (string) $xml->error_code : null;

            $data = [];
            if (isset($xml->response_data)) {
                $data = $this->parseResponseData($xml->response_data);
            } else {
                $data = $this->xmlToArray($xml);
                unset($data['success']);
                unset($data['error']);
                unset($data['error_code']);
            }

            return new EcontResponse($isSuccess, $errorMessage, $errorCode, $data);
        } catch (\Throwable $e) {
            return EcontResponse::error('Failed to parse response: ' . $e->getMessage());
        }
    }

    private function xmlToArray(\SimpleXMLElement $xml): array
    {
        $result = [];

        foreach ($xml->children() as $child) {
            $name = $this->snakeCaseToCamelCase($child->getName());
            $result[$name] = $this->xmlToValue($child);
        }

        foreach ($xml->attributes() as $name => $value) {
            $result[$this->snakeCaseToCamelCase($name)] = (string) $value;
        }

        return $result;
    }

    private function snakeCaseToCamelCase(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    private function parseResponseData(\SimpleXMLElement $responseData): array
    {
        $result = [];

        foreach ($responseData->children() as $child) {
            $childName = $child->getName();
            $value = $this->xmlToValue($child);

            $result[$this->snakeCaseToCamelCase($childName)] = $value;
        }

        return $result;
    }

    private function xmlToValue(\SimpleXMLElement $element): mixed
    {
        if (count($element->children()) === 0) {
            $value = trim((string) $element);

            if ($value === 'true') {
                return true;
            }
            if ($value === 'false') {
                return false;
            }

            return $value;
        }

        $childrenArray = [];
        $hasNumericKeys = false;
        $keyCount = 0;

        foreach ($element->children() as $child) {
            $childName = $child->getName();
            $childValue = $this->xmlToValue($child);

            if ($childName === 'item' && isset($child['key'])) {
                $key = (string) $child['key'];
                $childrenArray[$key] = $childValue;
                $hasNumericKeys = true;
            } else {
                $childrenArray[$childName] = $childValue;
            }
            $keyCount++;
        }

        if ($hasNumericKeys && count($childrenArray) === $keyCount && $keyCount > 0) {
            $values = array_values($childrenArray);
            if (count($values) === $keyCount) {
                return $values;
            }
        }

        $result = [];
        foreach ($element->children() as $child) {
            $childName = $child->getName();
            $value = $this->xmlToValue($child);

            $result[$this->snakeCaseToCamelCase($childName)] = $value;
        }

        return $result;
    }
}