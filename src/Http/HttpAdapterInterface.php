<?php

declare(strict_types=1);

namespace Econt\EcontApi\Http;

use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;

/**
 * Contract for HTTP adapters used by service classes.
 */
interface HttpAdapterInterface
{
    /**
     * Send a POST request to the given endpoint and return the decoded response.
     *
     * @param string  $endpoint Relative endpoint path (e.g. "Nomenclatures/NomenclaturesService.getCountries.json")
     * @param array<string, mixed> $payload  JSON-encodable request body
     *
     * @return array<string, mixed>
     *
     * @throws EcontApiException     When the API returns a business-level error
     * @throws EcontNetworkException When a transport-level error occurs
     */
    public function post(string $endpoint, array $payload): array;
}
