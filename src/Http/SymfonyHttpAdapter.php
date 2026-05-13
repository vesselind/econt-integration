<?php

declare(strict_types=1);

namespace Econt\EcontApi\Http;

use Econt\EcontApi\EcontConfiguration;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * HTTP adapter backed by Symfony HTTP Client.
 */
class SymfonyHttpAdapter implements HttpAdapterInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
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

        $this->logger?->debug('Econt API request', ['url' => $url, 'payload' => $payload]);

        try {
            $response = $this->client->request('POST', $url, [
                'json' => $payload,
                'auth_basic' => [
                    $this->configuration->getUsername(),
                    $this->configuration->getPassword(),
                ],
                'timeout' => $this->configuration->getTimeout(),
                'headers' => [
                    'Accept-Language' => $this->configuration->getLanguage(),
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray(false);
        } catch (ExceptionInterface $e) {
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
