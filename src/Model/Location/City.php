<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * City entity returned by the Econt API.
 */
class City
{
    public function __construct(
        private readonly ?int $id,
        private readonly Country $country,
        private readonly string $postCode,
        private readonly string $name,
        private readonly string $nameEn,
        private readonly ?string $regionName = null,
        private readonly ?string $regionNameEn = null,
        private readonly ?string $phoneCode = null,
        private readonly ?GeoLocation $location = null,
        private readonly ?bool $expressCityDeliveries = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function getRegionNameEn(): ?string
    {
        return $this->regionNameEn;
    }

    public function getPhoneCode(): ?string
    {
        return $this->phoneCode;
    }

    public function getLocation(): ?GeoLocation
    {
        return $this->location;
    }

    public function getExpressCityDeliveries(): ?bool
    {
        return $this->expressCityDeliveries;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            country: Country::fromArray($data['country'] ?? []),
            postCode: (string) ($data['postCode'] ?? ''),
            name: (string) ($data['name'] ?? ''),
            nameEn: (string) ($data['nameEn'] ?? ''),
            regionName: isset($data['regionName']) ? (string) $data['regionName'] : null,
            regionNameEn: isset($data['regionNameEn']) ? (string) $data['regionNameEn'] : null,
            phoneCode: isset($data['phoneCode']) ? (string) $data['phoneCode'] : null,
            location: isset($data['location']) && is_array($data['location'])
                ? GeoLocation::fromArray($data['location'])
                : null,
            expressCityDeliveries: isset($data['expressCityDeliveries'])
                ? (bool) $data['expressCityDeliveries']
                : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'country' => $this->country->toArray(),
            'postCode' => $this->postCode,
            'name' => $this->name,
            'nameEn' => $this->nameEn,
            'regionName' => $this->regionName,
            'regionNameEn' => $this->regionNameEn,
            'phoneCode' => $this->phoneCode,
            'location' => $this->location?->toArray(),
            'expressCityDeliveries' => $this->expressCityDeliveries,
        ];
    }
}
