<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Office;

use Econt\EcontApi\Model\AbstractModel;

class Office extends AbstractModel
{
    private ?string $officeCode;
    private ?string $officeName;
    private ?string $officeNameEn;
    private ?string $companyName;
    private ?string $companyNameEn;
    private ?string $email;
    private ?string $phone;
    private ?string $phone2;
    private ?OfficeAddress $address;
    private ?array $workHours;
    private ?bool $isMotorcycle;
    private ?bool $isDenominated;
    private ?bool $isCredit;
    private ?bool $isTakeBackOffice;
    private ?bool $isBehalfOf;
    private ?string $workingTime;
    private ?string $priority;
    private ?float $maxWeight;
    private ?float $maxPay;

    public function __construct(
        ?string $officeCode = null,
        ?string $officeName = null,
        ?string $officeNameEn = null,
        ?string $companyName = null,
        ?string $companyNameEn = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $phone2 = null,
        ?OfficeAddress $address = null,
        ?array $workHours = null,
        ?bool $isMotorcycle = null,
        ?bool $isDenominated = null,
        ?bool $isCredit = null,
        ?bool $isTakeBackOffice = null,
        ?bool $isBehalfOf = null,
        ?string $workingTime = null,
        ?string $priority = null,
        ?float $maxWeight = null,
        ?float $maxPay = null
    ) {
        $this->officeCode = $officeCode;
        $this->officeName = $officeName;
        $this->officeNameEn = $officeNameEn;
        $this->companyName = $companyName;
        $this->companyNameEn = $companyNameEn;
        $this->email = $email;
        $this->phone = $phone;
        $this->phone2 = $phone2;
        $this->address = $address;
        $this->workHours = $workHours;
        $this->isMotorcycle = $isMotorcycle;
        $this->isDenominated = $isDenominated;
        $this->isCredit = $isCredit;
        $this->isTakeBackOffice = $isTakeBackOffice;
        $this->isBehalfOf = $isBehalfOf;
        $this->workingTime = $workingTime;
        $this->priority = $priority;
        $this->maxWeight = $maxWeight;
        $this->maxPay = $maxPay;
    }

    public function getOfficeCode(): ?string
    {
        return $this->officeCode;
    }

    public function setOfficeCode(?string $officeCode): self
    {
        $this->officeCode = $officeCode;
        return $this;
    }

    public function getOfficeName(): ?string
    {
        return $this->officeName;
    }

    public function setOfficeName(?string $officeName): self
    {
        $this->officeName = $officeName;
        return $this;
    }

    public function getOfficeNameEn(): ?string
    {
        return $this->officeNameEn;
    }

    public function setOfficeNameEn(?string $officeNameEn): self
    {
        $this->officeNameEn = $officeNameEn;
        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getCompanyNameEn(): ?string
    {
        return $this->companyNameEn;
    }

    public function setCompanyNameEn(?string $companyNameEn): self
    {
        $this->companyNameEn = $companyNameEn;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = $phone2;
        return $this;
    }

    public function getAddress(): ?OfficeAddress
    {
        return $this->address;
    }

    public function setAddress(?OfficeAddress $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getWorkHours(): ?array
    {
        return $this->workHours;
    }

    public function setWorkHours(?array $workHours): self
    {
        $this->workHours = $workHours;
        return $this;
    }

    public function isMotorcycle(): ?bool
    {
        return $this->isMotorcycle;
    }

    public function setIsMotorcycle(?bool $isMotorcycle): self
    {
        $this->isMotorcycle = $isMotorcycle;
        return $this;
    }

    public function isDenominated(): ?bool
    {
        return $this->isDenominated;
    }

    public function setIsDenominated(?bool $isDenominated): self
    {
        $this->isDenominated = $isDenominated;
        return $this;
    }

    public function isCredit(): ?bool
    {
        return $this->isCredit;
    }

    public function setIsCredit(?bool $isCredit): self
    {
        $this->isCredit = $isCredit;
        return $this;
    }

    public function isTakeBackOffice(): ?bool
    {
        return $this->isTakeBackOffice;
    }

    public function setIsTakeBackOffice(?bool $isTakeBackOffice): self
    {
        $this->isTakeBackOffice = $isTakeBackOffice;
        return $this;
    }

    public function isBehalfOf(): ?bool
    {
        return $this->isBehalfOf;
    }

    public function setIsBehalfOf(?bool $isBehalfOf): self
    {
        $this->isBehalfOf = $isBehalfOf;
        return $this;
    }

    public function getWorkingTime(): ?string
    {
        return $this->workingTime;
    }

    public function setWorkingTime(?string $workingTime): self
    {
        $this->workingTime = $workingTime;
        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getMaxWeight(): ?float
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(?float $maxWeight): self
    {
        $this->maxWeight = $maxWeight;
        return $this;
    }

    public function getMaxPay(): ?float
    {
        return $this->maxPay;
    }

    public function setMaxPay(?float $maxPay): self
    {
        $this->maxPay = $maxPay;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $workHours = null;
        if (isset($data['workHours']) && is_array($data['workHours'])) {
            $workHours = array_map(
                fn($item) => OfficeWorkHours::fromArray($item),
                $data['workHours']
            );
        }

        $address = null;
        if (isset($data['address']) && is_array($data['address'])) {
            $address = OfficeAddress::fromArray($data['address']);
        }

        return new self(
            $data['officeCode'] ?? null,
            $data['officeName'] ?? null,
            $data['officeNameEn'] ?? null,
            $data['companyName'] ?? null,
            $data['companyNameEn'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['phone2'] ?? null,
            $address,
            $workHours,
            $data['isMotorcycle'] ?? null,
            $data['isDenominated'] ?? null,
            $data['isCredit'] ?? null,
            $data['isTakeBackOffice'] ?? null,
            $data['isBehalfOf'] ?? null,
            $data['workingTime'] ?? null,
            $data['priority'] ?? null,
            isset($data['maxWeight']) ? (float) $data['maxWeight'] : null,
            isset($data['maxPay']) ? (float) $data['maxPay'] : null
        );
    }
}