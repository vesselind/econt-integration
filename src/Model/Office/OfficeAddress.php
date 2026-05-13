<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Office;

use Econt\EcontApi\Model\AbstractModel;

class OfficeAddress extends AbstractModel
{
    private ?string $city;
    private ?string $cityId;
    private ?string $quarter;
    private ?string $street;
    private ?string $streetNum;
    private ?string $other;
    private ?string $postalCode;
    private ?string $addressNote;
    private ?float $latitude;
    private ?float $longitude;

    public function __construct(
        ?string $city = null,
        ?string $cityId = null,
        ?string $quarter = null,
        ?string $street = null,
        ?string $streetNum = null,
        ?string $other = null,
        ?string $postalCode = null,
        ?string $addressNote = null,
        ?float $latitude = null,
        ?float $longitude = null
    ) {
        $this->city = $city;
        $this->cityId = $cityId;
        $this->quarter = $quarter;
        $this->street = $street;
        $this->streetNum = $streetNum;
        $this->other = $other;
        $this->postalCode = $postalCode;
        $this->addressNote = $addressNote;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCityId(): ?string
    {
        return $this->cityId;
    }

    public function setCityId(?string $cityId): self
    {
        $this->cityId = $cityId;
        return $this;
    }

    public function getQuarter(): ?string
    {
        return $this->quarter;
    }

    public function setQuarter(?string $quarter): self
    {
        $this->quarter = $quarter;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getStreetNum(): ?string
    {
        return $this->streetNum;
    }

    public function setStreetNum(?string $streetNum): self
    {
        $this->streetNum = $streetNum;
        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getAddressNote(): ?string
    {
        return $this->addressNote;
    }

    public function setAddressNote(?string $addressNote): self
    {
        $this->addressNote = $addressNote;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['city'] ?? null,
            $data['cityId'] ?? null,
            $data['quarter'] ?? null,
            $data['street'] ?? null,
            $data['streetNum'] ?? null,
            $data['other'] ?? null,
            $data['postalCode'] ?? null,
            $data['addressNote'] ?? null,
            isset($data['latitude']) ? (float) $data['latitude'] : null,
            isset($data['longitude']) ? (float) $data['longitude'] : null
        );
    }
}