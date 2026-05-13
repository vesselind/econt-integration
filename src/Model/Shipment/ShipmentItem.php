<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Model\AbstractModel;

class ShipmentItem extends AbstractModel
{
    private ?string $keyId;
    private ?int $quantity;
    private ?string $description;
    private ?float $weight;
    private ?float $price;
    private ?string $currency;
    private ?string $europeanTradeMark;
    private ?bool $isFragile;
    private ?bool $isNoReturn;

    public function __construct(
        ?string $keyId = null,
        ?int $quantity = null,
        ?string $description = null,
        ?float $weight = null,
        ?float $price = null,
        ?string $currency = null,
        ?string $europeanTradeMark = null,
        ?bool $isFragile = null,
        ?bool $isNoReturn = null
    ) {
        $this->keyId = $keyId;
        $this->quantity = $quantity;
        $this->description = $description;
        $this->weight = $weight;
        $this->price = $price;
        $this->currency = $currency;
        $this->europeanTradeMark = $europeanTradeMark;
        $this->isFragile = $isFragile;
        $this->isNoReturn = $isNoReturn;
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function setKeyId(?string $keyId): self
    {
        $this->keyId = $keyId;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getEuropeanTradeMark(): ?string
    {
        return $this->europeanTradeMark;
    }

    public function setEuropeanTradeMark(?string $europeanTradeMark): self
    {
        $this->europeanTradeMark = $europeanTradeMark;
        return $this;
    }

    public function isFragile(): ?bool
    {
        return $this->isFragile;
    }

    public function setIsFragile(?bool $isFragile): self
    {
        $this->isFragile = $isFragile;
        return $this;
    }

    public function isNoReturn(): ?bool
    {
        return $this->isNoReturn;
    }

    public function setIsNoReturn(?bool $isNoReturn): self
    {
        $this->isNoReturn = $isNoReturn;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['keyId'] ?? null,
            isset($data['quantity']) ? (int) $data['quantity'] : null,
            $data['description'] ?? null,
            isset($data['weight']) ? (float) $data['weight'] : null,
            isset($data['price']) ? (float) $data['price'] : null,
            $data['currency'] ?? null,
            $data['europeanTradeMark'] ?? null,
            $data['isFragile'] ?? null,
            $data['isNoReturn'] ?? null
        );
    }
}