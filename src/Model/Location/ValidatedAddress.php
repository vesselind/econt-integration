<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * Address with validation status returned by the validateAddress endpoint.
 */
class ValidatedAddress extends Address
{
    public function __construct(
        City $city,
        private readonly string $validationStatus,
        ?int $id = null,
        ?string $fullAddress = null,
        ?string $quarter = null,
        ?string $street = null,
        ?string $num = null,
        ?string $other = null,
        ?GeoLocation $location = null,
        ?string $zip = null,
    ) {
        parent::__construct(
            city: $city,
            id: $id,
            fullAddress: $fullAddress,
            quarter: $quarter,
            street: $street,
            num: $num,
            other: $other,
            location: $location,
            zip: $zip,
        );
    }

    public function getValidationStatus(): string
    {
        return $this->validationStatus;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $addressData = $data['address'] ?? $data;
        $city = City::fromArray($addressData['city'] ?? []);
        $location = isset($addressData['location']) && is_array($addressData['location'])
            ? GeoLocation::fromArray($addressData['location'])
            : null;

        return new self(
            city: $city,
            validationStatus: (string) ($data['validationStatus'] ?? 'invalid'),
            id: isset($addressData['id']) ? (int) $addressData['id'] : null,
            fullAddress: isset($addressData['fullAddress']) ? (string) $addressData['fullAddress'] : null,
            quarter: isset($addressData['quarter']) ? (string) $addressData['quarter'] : null,
            street: isset($addressData['street']) ? (string) $addressData['street'] : null,
            num: isset($addressData['num']) ? (string) $addressData['num'] : null,
            other: isset($addressData['other']) ? (string) $addressData['other'] : null,
            location: $location,
            zip: isset($addressData['zip']) ? (string) $addressData['zip'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'validationStatus' => $this->validationStatus,
        ]);
    }
}
