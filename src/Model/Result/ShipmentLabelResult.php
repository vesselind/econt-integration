<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Result of createLabel / validateLabel operations.
 */
class ShipmentLabelResult
{
    /**
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        private readonly string $waybillNumber,
        private readonly ?PriceCalculationResult $price,
        private readonly array $rawResponse,
    ) {
    }

    public function getWaybillNumber(): string
    {
        return $this->waybillNumber;
    }

    public function getPrice(): ?PriceCalculationResult
    {
        return $this->price;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRawResponse(): array
    {
        return $this->rawResponse;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $label = $data['label'] ?? $data;
        $priceData = $label['price'] ?? null;

        return new self(
            waybillNumber: (string) ($label['shipmentNumber'] ?? $label['waybillNumber'] ?? ''),
            price: $priceData !== null ? PriceCalculationResult::fromArray((array) $priceData) : null,
            rawResponse: $data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'waybillNumber' => $this->waybillNumber,
            'price' => $this->price?->toArray(),
        ];
    }
}
