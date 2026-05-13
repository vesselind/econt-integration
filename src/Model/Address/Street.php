<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Address;

use Econt\EcontApi\Model\AbstractModel;

class Street extends AbstractModel
{
    private ?string $streetId;
    private ?string $streetName;
    private ?string $streetNameEn;
    private ?string $cityId;
    private ?string $postCode;
    private ?string $streetType;
    private ?bool $isActive;

    public function __construct(
        ?string $streetId = null,
        ?string $streetName = null,
        ?string $streetNameEn = null,
        ?string $cityId = null,
        ?string $postCode = null,
        ?string $streetType = null,
        ?bool $isActive = null
    ) {
        $this->streetId = $streetId;
        $this->streetName = $streetName;
        $this->streetNameEn = $streetNameEn;
        $this->cityId = $cityId;
        $this->postCode = $postCode;
        $this->streetType = $streetType;
        $this->isActive = $isActive;
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

    public function getStreetNameEn(): ?string
    {
        return $this->streetNameEn;
    }

    public function setStreetNameEn(?string $streetNameEn): self
    {
        $this->streetNameEn = $streetNameEn;
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

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): self
    {
        $this->postCode = $postCode;
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['streetId'] ?? null,
            $data['streetName'] ?? null,
            $data['streetNameEn'] ?? null,
            $data['cityId'] ?? null,
            $data['postCode'] ?? null,
            $data['streetType'] ?? null,
            $data['isActive'] ?? null
        );
    }
}