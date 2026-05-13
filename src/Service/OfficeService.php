<?php

declare(strict_types=1);

namespace Econt\EcontApi\Service;

use Econt\EcontApi\Collection\OfficeCollection;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Http\HttpAdapterInterface;
use Econt\EcontApi\Model\Office\Office;

/**
 * Service for retrieving Econt office information.
 */
class OfficeService
{
    public function __construct(
        private readonly HttpAdapterInterface $adapter,
    ) {
    }

    /**
     * Retrieve offices, optionally filtered by country code and/or city ID.
     *
     * @throws EcontApiException
     * @throws EcontNetworkException
     */
    public function getOffices(?string $countryCode = null, ?int $cityId = null): OfficeCollection
    {
        $payload = [];

        if ($countryCode !== null) {
            $payload['countryCode'] = $countryCode;
        }

        if ($cityId !== null) {
            $payload['cityID'] = (string) $cityId;
        }

        $response = $this->adapter->post(
            'Nomenclatures/NomenclaturesService.getOffices.json',
            $payload,
        );

        $offices = array_map(
            static fn (array $item): Office => Office::fromArray($item),
            (array) ($response['offices'] ?? []),
        );

        return new OfficeCollection($offices);
    }
}
