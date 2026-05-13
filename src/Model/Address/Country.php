<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Address;

use Econt\EcontApi\Model\AbstractModel;

class Country extends AbstractModel
{
    private ?string $countryCode;
    private ?string $countryName;
    private ?string $countryNameEn;
    private ?bool $isEu;
    private ?string $postCodePattern;

    public function __construct(
        ?string $countryCode = null,
        ?string $countryName = null,
        ?string $countryNameEn = null,
        ?bool $isEu = null,
        ?string $postCodePattern = null
    ) {
        $this->countryCode = $countryCode;
        $this->countryName = $countryName;
        $this->countryNameEn = $countryNameEn;
        $this->isEu = $isEu;
        $this->postCodePattern = $postCodePattern;
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

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(?string $countryName): self
    {
        $this->countryName = $countryName;
        return $this;
    }

    public function getCountryNameEn(): ?string
    {
        return $this->countryNameEn;
    }

    public function setCountryNameEn(?string $countryNameEn): self
    {
        $this->countryNameEn = $countryNameEn;
        return $this;
    }

    public function isEu(): ?bool
    {
        return $this->isEu;
    }

    public function setIsEu(?bool $isEu): self
    {
        $this->isEu = $isEu;
        return $this;
    }

    public function getPostCodePattern(): ?string
    {
        return $this->postCodePattern;
    }

    public function setPostCodePattern(?string $postCodePattern): self
    {
        $this->postCodePattern = $postCodePattern;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['countryCode'] ?? null,
            $data['countryName'] ?? null,
            $data['countryNameEn'] ?? null,
            $data['isEu'] ?? null,
            $data['postCodePattern'] ?? null
        );
    }
}