<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Client\EcontClientInterface;
use Econt\EcontApi\Model\Office\Office;
use Econt\EcontApi\Model\Response\EcontResponse;
use Econt\EcontApi\Service\Contract\OfficeServiceInterface;

class OfficeService implements OfficeServiceInterface
{
    private const SERVICE_NAME = 'Services.ExchangeRates.requestOffices';

    private EcontClientInterface $client;

    public function __construct(EcontClientInterface $client)
    {
        $this->client = $client;
    }

    public function requestOffices(): EcontResponse
    {
        return $this->client->request(self::SERVICE_NAME, [
            'type' => 'OFFICEE',
        ]);
    }

    public function requestOfficesExDay(string $cityName, ?string $countryCode = null): EcontResponse
    {
        $data = [
            'type' => 'OFFICEE',
            'city' => $cityName,
        ];

        if ($countryCode !== null) {
            $data['country'] = $countryCode;
        }

        return $this->client->request(self::SERVICE_NAME, $data);
    }

    public function requestOffice(string $officeCode): EcontResponse
    {
        return $this->client->request(self::SERVICE_NAME, [
            'type' => 'OFFICEE',
            'office_code' => $officeCode,
        ]);
    }

    public function getOffices(?string $cityName = null, ?string $countryCode = null): array
    {
        $response = $cityName !== null
            ? $this->requestOfficesExDay($cityName, $countryCode)
            : $this->requestOffices();

        if (!$response->isSuccess()) {
            return [];
        }

        $data = $response->getData();
        if (!isset($data['offices']) || !is_array($data['offices'])) {
            return [];
        }

        return array_map(
            fn($item) => Office::fromArray($item),
            $data['offices']
        );
    }

    public function getOffice(string $officeCode): ?Office
    {
        $response = $this->requestOffice($officeCode);

        if (!$response->isSuccess()) {
            return null;
        }

        $data = $response->getData();
        if (!isset($data['offices']) || !is_array($data['offices']) || count($data['offices']) === 0) {
            return null;
        }

        return Office::fromArray($data['offices'][0]);
    }
}