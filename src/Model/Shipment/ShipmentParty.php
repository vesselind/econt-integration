<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Model\AbstractModel;
use Econt\EcontApi\Model\Address\Address;

class ShipmentParty extends AbstractModel
{
    private ?string $clientId;
    private ?string $type;
    private ?string $name;
    private ?string $companyName;
    private ?string $bulstat;
    private ?string $egn;
    private ?string $email;
    private ?string $phone;
    private ?string $phone2;
    private ?Address $address;
    private ?string $officeCode;
    private ?bool $isBulstatValidated;

    public function __construct(
        ?string $clientId = null,
        ?string $type = null,
        ?string $name = null,
        ?string $companyName = null,
        ?string $bulstat = null,
        ?string $egn = null,
        ?string $email = null,
        ?string $phone = null,
        ?string $phone2 = null,
        ?Address $address = null,
        ?string $officeCode = null,
        ?bool $isBulstatValidated = null
    ) {
        $this->clientId = $clientId;
        $this->type = $type;
        $this->name = $name;
        $this->companyName = $companyName;
        $this->bulstat = $bulstat;
        $this->egn = $egn;
        $this->email = $email;
        $this->phone = $phone;
        $this->phone2 = $phone2;
        $this->address = $address;
        $this->officeCode = $officeCode;
        $this->isBulstatValidated = $isBulstatValidated;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
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

    public function getBulstat(): ?string
    {
        return $this->bulstat;
    }

    public function setBulstat(?string $bulstat): self
    {
        $this->bulstat = $bulstat;
        return $this;
    }

    public function getEgn(): ?string
    {
        return $this->egn;
    }

    public function setEgn(?string $egn): self
    {
        $this->egn = $egn;
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;
        return $this;
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

    public function isBulstatValidated(): ?bool
    {
        return $this->isBulstatValidated;
    }

    public function setIsBulstatValidated(?bool $isBulstatValidated): self
    {
        $this->isBulstatValidated = $isBulstatValidated;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $address = null;
        if (isset($data['address']) && is_array($data['address'])) {
            $address = Address::fromArray($data['address']);
        }

        return new self(
            $data['clientId'] ?? null,
            $data['type'] ?? null,
            $data['name'] ?? null,
            $data['companyName'] ?? null,
            $data['bulstat'] ?? null,
            $data['egn'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['phone2'] ?? null,
            $address,
            $data['officeCode'] ?? null,
            $data['isBulstatValidated'] ?? null
        );
    }
}