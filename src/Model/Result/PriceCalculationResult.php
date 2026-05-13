<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Price calculation result returned from calculate mode.
 */
class PriceCalculationResult
{
    /**
     * @param array<string, mixed> $details
     */
    public function __construct(
        private readonly float $totalPrice,
        private readonly string $currency,
        private readonly array $details = [],
    ) {
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            totalPrice: (float) ($data['totalPrice'] ?? 0.0),
            currency: (string) ($data['currency'] ?? 'BGN'),
            details: $data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'totalPrice' => $this->totalPrice,
            'currency' => $this->currency,
        ];
    }
}
