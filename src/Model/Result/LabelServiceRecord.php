<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * A single pricing service line returned inside a label response.
 */
class LabelServiceRecord
{
    public function __construct(
        public readonly string $type,
        public readonly string $description,
        public readonly int $count,
        public readonly string $paymentSide,
        public readonly float $price,
        public readonly string $currency,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: (string) ($data['type'] ?? ''),
            description: (string) ($data['description'] ?? ''),
            count: (int) ($data['count'] ?? 1),
            paymentSide: (string) ($data['paymentSide'] ?? ''),
            price: (float) ($data['price'] ?? 0.0),
            currency: (string) ($data['currency'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
            'count' => $this->count,
            'paymentSide' => $this->paymentSide,
            'price' => $this->price,
            'currency' => $this->currency,
        ];
    }
}

