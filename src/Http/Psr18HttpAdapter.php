<?php

declare(strict_types=1);

namespace Econt\EcontApi\Http;

use Econt\EcontApi\EcontConfiguration;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * HTTP adapter backed by any PSR-18 client.
 */
class Psr18HttpAdapter implements HttpAdapterInterface
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly EcontConfiguration $configuration,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function post(string $endpoint, array $payload): array
    {
        $url = $this->configuration->getBaseUrl() . $endpoint;
        $jsonBody = (string) json_encode($payload);
        $credentials = base64_encode(
            $this->configuration->getUsername() . ':' . $this->configuration->getPassword()
        );

        $this->logger?->debug('Econt API request', ['url' => $url, 'payload' => $payload]);

        try {
            $stream = $this->streamFactory->createStream($jsonBody);
            $request = $this->requestFactory->createRequest('POST', $url)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json')
                ->withHeader('Authorization', 'Basic ' . $credentials)
                ->withHeader('Accept-Language', $this->configuration->getLanguage())
                ->withBody($stream);

            $response = $this->client->sendRequest($request);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            throw new EcontNetworkException(
                'Network error communicating with Econt API: ' . $e->getMessage(),
                0,
                $e,
            );
        } catch (\Throwable $e) {
            throw new EcontNetworkException(
                'Unexpected error communicating with Econt API: ' . $e->getMessage(),
                0,
                $e,
            );
        }

        $data = json_decode($body, true);

        if (!is_array($data)) {
            throw new EcontNetworkException(
                sprintf('Invalid JSON response from Econt API. Status: %d', $statusCode),
            );
        }

        $this->logger?->debug('Econt API response', ['status' => $statusCode, 'body' => $data]);

        if (isset($data['error'])) {
            $error = $data['error'];
            throw new EcontApiException(
                (string) ($error['message'] ?? 'Unknown API error'),
                (string) ($error['code'] ?? ''),
                (string) ($error['message'] ?? ''),
            );
        }

        if ($statusCode >= 400) {
            throw new EcontNetworkException(
                sprintf('HTTP %d received from Econt API for endpoint: %s', $statusCode, $endpoint),
                $statusCode,
            );
        }

        return $data;
    }
}
