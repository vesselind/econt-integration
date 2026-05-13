<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Address;

use Econt\EcontApi\Model\AbstractModel;

class Address extends AbstractModel
{
    private ?string $cityId;
    private ?string $cityName;
    private ?string $quarterId;
    private ?string $quarterName;
    private ?string $streetId;
    private ?string $streetName;
    private ?string $streetType;
    private ?string $streetNum;
    private ?string $buildingNum;
    private ?string $entranceNum;
    private ?string $floorNum;
    private ?string $apartmentNum;
    private ?string $other;
    private ?string $postCode;
    private ?string $addressNote;
    private ?float $latitude;
    private ?float $longitude;

    public function __construct(
        ?string $cityId = null,
        ?string $cityName = null,
        ?string $quarterId = null,
        ?string $quarterName = null,
        ?string $streetId = null,
        ?string $streetName = null,
        ?string $streetType = null,
        ?string $streetNum = null,
        ?string $buildingNum = null,
        ?string $entranceNum = null,
        ?string $floorNum = null,
        ?string $apartmentNum = null,
        ?string $other = null,
        ?string $postCode = null,
        ?string $addressNote = null,
        ?float $latitude = null,
        ?float $longitude = null
    ) {
        $this->cityId = $cityId;
        $this->cityName = $cityName;
        $this->quarterId = $quarterId;
        $this->quarterName = $quarterName;
        $this->streetId = $streetId;
        $this->streetName = $streetName;
        $this->streetType = $streetType;
        $this->streetNum = $streetNum;
        $this->buildingNum = $buildingNum;
        $this->entranceNum = $entranceNum;
        $this->floorNum = $floorNum;
        $this->apartmentNum = $apartmentNum;
        $this->other = $other;
        $this->postCode = $postCode;
        $this->addressNote = $addressNote;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function getCity(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(?string $cityName): self
    {
        $this->cityName = $cityName;
        return $this;
    }

    public function getQuarterId(): ?string
    {
        return $this->quarterId;
    }

    public function setQuarterId(?string $quarterId): self
    {
        $this->quarterId = $quarterId;
        return $this;
    }

    public function getQuarterName(): ?string
    {
        return $this->quarterName;
    }

    public function setQuarterName(?string $quarterName): self
    {
        $this->quarterName = $quarterName;
        return $this;
    }

    public function getStreetId(): ?string
    {
        return $this->streetId;
    }

    public function setStreetId(?string $streetId): self
    {
        $this->streetId = $streetId;
        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function getStreetType(): ?string
    {
        return $this->streetType;
    }

    public function setStreetType(?string $streetType): self
    {
        $this->streetType = $streetType;
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

    public function getBuildingNum(): ?string
    {
        return $this->buildingNum;
    }

    public function setBuildingNum(?string $buildingNum): self
    {
        $this->buildingNum = $buildingNum;
        return $this;
    }

    public function getEntranceNum(): ?string
    {
        return $this->entranceNum;
    }

    public function setEntranceNum(?string $entranceNum): self
    {
        $this->entranceNum = $entranceNum;
        return $this;
    }

    public function getFloorNum(): ?string
    {
        return $this->floorNum;
    }

    public function setFloorNum(?string $floorNum): self
    {
        $this->floorNum = $floorNum;
        return $this;
    }

    public function getApartmentNum(): ?string
    {
        return $this->apartmentNum;
    }

    public function setApartmentNum(?string $apartmentNum): self
    {
        $this->apartmentNum = $apartmentNum;
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

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): self
    {
        $this->postCode = $postCode;
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
            $data['cityId'] ?? null,
            $data['cityName'] ?? null,
            $data['quarterId'] ?? null,
            $data['quarterName'] ?? null,
            $data['streetId'] ?? null,
            $data['streetName'] ?? null,
            $data['streetType'] ?? null,
            $data['streetNum'] ?? null,
            $data['buildingNum'] ?? null,
            $data['entranceNum'] ?? null,
            $data['floorNum'] ?? null,
            $data['apartmentNum'] ?? null,
            $data['other'] ?? null,
            $data['postCode'] ?? null,
            $data['addressNote'] ?? null,
            isset($data['latitude']) ? (float) $data['latitude'] : null,
            isset($data['longitude']) ? (float) $data['longitude'] : null
        );
    }

    public function toFormattedString(): string
    {
        $parts = [];

        if ($this->streetName !== null) {
            $streetPart = $this->streetName;
            if ($this->streetType !== null) {
                $streetPart = $this->streetType . ' ' . $streetPart;
            }
            if ($this->streetNum !== null) {
                $streetPart .= ' ' . $this->streetNum;
            }
            $parts[] = $streetPart;
        }

        if ($this->buildingNum !== null) {
            $parts[] = 'бл. ' . $this->buildingNum;
        }

        if ($this->entranceNum !== null) {
            $parts[] = 'вх. ' . $this->entranceNum;
        }

        if ($this->floorNum !== null) {
            $parts[] = 'ет. ' . $this->floorNum;
        }

        if ($this->apartmentNum !== null) {
            $parts[] = 'ап. ' . $this->apartmentNum;
        }

        if ($this->cityName !== null) {
            $parts[] = $this->cityName;
        }

        if ($this->postCode !== null) {
            $parts[] = $this->postCode;
        }

        return implode(', ', $parts);
    }
}