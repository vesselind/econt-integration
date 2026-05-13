<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

/**
 * Optional additional services for a shipping label.
 */
class ShippingLabelServices
{
    public function __construct(
        private readonly ?float $declaredValueAmount = null,
        private readonly ?string $declaredValueCurrency = null,
        private readonly ?bool $deliveryReceipt = null,
        private readonly ?string $priorityTimeFrom = null,
        private readonly ?string $priorityTimeTo = null,
        private readonly ?bool $smsNotification = null,
    ) {
    }

    public function getDeclaredValueAmount(): ?float
    {
        return $this->declaredValueAmount;
    }

    public function getDeclaredValueCurrency(): ?string
    {
        return $this->declaredValueCurrency;
    }

    public function getDeliveryReceipt(): ?bool
    {
        return $this->deliveryReceipt;
    }

    public function getPriorityTimeFrom(): ?string
    {
        return $this->priorityTimeFrom;
    }

    public function getPriorityTimeTo(): ?string
    {
        return $this->priorityTimeTo;
    }

    public function getSmsNotification(): ?bool
    {
        return $this->smsNotification;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            declaredValueAmount: isset($data['declaredValueAmount'])
                ? (float) $data['declaredValueAmount']
                : null,
            declaredValueCurrency: isset($data['declaredValueCurrency'])
                ? (string) $data['declaredValueCurrency']
                : null,
            deliveryReceipt: isset($data['deliveryReceipt']) ? (bool) $data['deliveryReceipt'] : null,
            priorityTimeFrom: isset($data['priorityTimeFrom']) ? (string) $data['priorityTimeFrom'] : null,
            priorityTimeTo: isset($data['priorityTimeTo']) ? (string) $data['priorityTimeTo'] : null,
            smsNotification: isset($data['smsNotification']) ? (bool) $data['smsNotification'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'declaredValueAmount' => $this->declaredValueAmount,
            'declaredValueCurrency' => $this->declaredValueCurrency,
            'deliveryReceipt' => $this->deliveryReceipt,
            'priorityTimeFrom' => $this->priorityTimeFrom,
            'priorityTimeTo' => $this->priorityTimeTo,
            'smsNotification' => $this->smsNotification,
        ];
    }
}
