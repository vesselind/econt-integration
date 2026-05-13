<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Edition metadata returned as part of a label response.
 */
class ShipmentEdition
{
    public function __construct(
        public readonly int $shipmentNum,
        public readonly int $editionNum,
        public readonly string $editionType,
        public readonly string $editionError,
        public readonly string $price,
        public readonly string $currency,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            shipmentNum: (int) ($data['shipmentNum'] ?? 0),
            editionNum: (int) ($data['editionNum'] ?? 0),
            editionType: (string) ($data['editionType'] ?? ''),
            editionError: (string) ($data['editionError'] ?? ''),
            price: (string) ($data['price'] ?? ''),
            currency: (string) ($data['currency'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'shipmentNum' => $this->shipmentNum,
            'editionNum' => $this->editionNum,
            'editionType' => $this->editionType,
            'editionError' => $this->editionError,
            'price' => $this->price,
            'currency' => $this->currency,
        ];
    }
}

