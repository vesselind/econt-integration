<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * Address entity used across Econt API requests and responses.
 */
class Address
{
    public function __construct(
        private readonly City $city,
        private readonly ?int $id = null,
        private readonly ?string $fullAddress = null,
        private readonly ?string $quarter = null,
        private readonly ?string $street = null,
        private readonly ?string $num = null,
        private readonly ?string $other = null,
        private readonly ?GeoLocation $location = null,
        private readonly ?string $zip = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function getQuarter(): ?string
    {
        return $this->quarter;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function getLocation(): ?GeoLocation
    {
        return $this->location;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            city: City::fromArray($data['city'] ?? []),
            id: isset($data['id']) ? (int) $data['id'] : null,
            fullAddress: isset($data['fullAddress']) ? (string) $data['fullAddress'] : null,
            quarter: isset($data['quarter']) ? (string) $data['quarter'] : null,
            street: isset($data['street']) ? (string) $data['street'] : null,
            num: isset($data['num']) ? (string) $data['num'] : null,
            other: isset($data['other']) ? (string) $data['other'] : null,
            location: isset($data['location']) && is_array($data['location'])
                ? GeoLocation::fromArray($data['location'])
                : null,
            zip: isset($data['zip']) ? (string) $data['zip'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city->toArray(),
            'fullAddress' => $this->fullAddress,
            'quarter' => $this->quarter,
            'street' => $this->street,
            'num' => $this->num,
            'other' => $this->other,
            'location' => $this->location?->toArray(),
            'zip' => $this->zip,
        ];
    }
}
