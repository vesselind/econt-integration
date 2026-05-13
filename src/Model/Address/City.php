<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Address;

use Econt\EcontApi\Model\AbstractModel;

class City extends AbstractModel
{
    private ?string $cityId;
    private ?string $cityName;
    private ?string $cityNameEn;
    private ?string $municipality;
    private ?string $region;
    private ?string $postCode;
    private ?float $latitude;
    private ?float $longitude;
    private ?bool $isCapital;
    private ?bool $isActive;
    private ?string $countryCode;

    public function __construct(
        ?string $cityId = null,
        ?string $cityName = null,
        ?string $cityNameEn = null,
        ?string $municipality = null,
        ?string $region = null,
        ?string $postCode = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?bool $isCapital = null,
        ?bool $isActive = null,
        ?string $countryCode = null
    ) {
        $this->cityId = $cityId;
        $this->cityName = $cityName;
        $this->cityNameEn = $cityNameEn;
        $this->municipality = $municipality;
        $this->region = $region;
        $this->postCode = $postCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->isCapital = $isCapital;
        $this->isActive = $isActive;
        $this->countryCode = $countryCode;
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

    public function setCityName(?string $cityName): self
    {
        $this->cityName = $cityName;
        return $this;
    }

    public function getCityNameEn(): ?string
    {
        return $this->cityNameEn;
    }

    public function setCityNameEn(?string $cityNameEn): self
    {
        $this->cityNameEn = $cityNameEn;
        return $this;
    }

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function setMunicipality(?string $municipality): self
    {
        $this->municipality = $municipality;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
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

    public function isCapital(): ?bool
    {
        return $this->isCapital;
    }

    public function setIsCapital(?bool $isCapital): self
    {
        $this->isCapital = $isCapital;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['cityId'] ?? null,
            $data['cityName'] ?? null,
            $data['cityNameEn'] ?? null,
            $data['municipality'] ?? null,
            $data['region'] ?? null,
            $data['postCode'] ?? null,
            isset($data['latitude']) ? (float) $data['latitude'] : null,
            isset($data['longitude']) ? (float) $data['longitude'] : null,
            $data['isCapital'] ?? null,
            $data['isActive'] ?? null,
            $data['countryCode'] ?? null
        );
    }
}