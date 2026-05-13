<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Address;

use Econt\EcontApi\Model\AbstractModel;

class Quarter extends AbstractModel
{
    private ?string $quarterId;
    private ?string $quarterName;
    private ?string $quarterNameEn;
    private ?string $cityId;
    private ?string $postCode;
    private ?bool $isActive;

    public function __construct(
        ?string $quarterId = null,
        ?string $quarterName = null,
        ?string $quarterNameEn = null,
        ?string $cityId = null,
        ?string $postCode = null,
        ?bool $isActive = null
    ) {
        $this->quarterId = $quarterId;
        $this->quarterName = $quarterName;
        $this->quarterNameEn = $quarterNameEn;
        $this->cityId = $cityId;
        $this->postCode = $postCode;
        $this->isActive = $isActive;
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

    public function getQuarterNameEn(): ?string
    {
        return $this->quarterNameEn;
    }

    public function setQuarterNameEn(?string $quarterNameEn): self
    {
        $this->quarterNameEn = $quarterNameEn;
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
            $data['quarterId'] ?? null,
            $data['quarterName'] ?? null,
            $data['quarterNameEn'] ?? null,
            $data['cityId'] ?? null,
            $data['postCode'] ?? null,
            $data['isActive'] ?? null
        );
    }
}