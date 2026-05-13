<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

/**
 * A single item in the shipment packing list.
 */
class PackingListElement
{
    public function __construct(
        private readonly string $inventoryNum,
        private readonly string $description,
        private readonly float $weight,
        private readonly float $price,
        private readonly int $count,
        private readonly ?string $file = null,
    ) {
    }

    public function getInventoryNum(): string
    {
        return $this->inventoryNum;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            inventoryNum: (string) ($data['inventoryNum'] ?? ''),
            description: (string) ($data['description'] ?? ''),
            weight: (float) ($data['weight'] ?? 0.0),
            price: (float) ($data['price'] ?? 0.0),
            count: (int) ($data['count'] ?? 0),
            file: isset($data['file']) ? (string) $data['file'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'inventoryNum' => $this->inventoryNum,
            'description' => $this->description,
            'weight' => $this->weight,
            'price' => $this->price,
            'count' => $this->count,
            'file' => $this->file,
        ];
    }
}
