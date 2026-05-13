<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Result of createLabel / validateLabel / updateLabel operations.
 * The full label data is available via getLabel().
 */
class ShipmentLabelResult
{
    public function __construct(
        private readonly string $waybillNumber,
        private readonly ?LabelDetails $label,
        private readonly ?string $blockingPaymentUrl,
        private readonly ?string $courierRequestId,
        private readonly ?bool $payAfterAcceptIgnored,
        private readonly string $delayedDeliveryWarning,
        private readonly string $delayedRequestWarning,
    ) {
    }

    public function getWaybillNumber(): string
    {
        return $this->waybillNumber;
    }

    public function getLabel(): ?LabelDetails
    {
        return $this->label;
    }

    public function getBlockingPaymentUrl(): ?string
    {
        return $this->blockingPaymentUrl;
    }

    public function getCourierRequestId(): ?string
    {
        return $this->courierRequestId;
    }

    public function isPayAfterAcceptIgnored(): ?bool
    {
        return $this->payAfterAcceptIgnored;
    }

    public function getDelayedDeliveryWarning(): string
    {
        return $this->delayedDeliveryWarning;
    }

    public function getDelayedRequestWarning(): string
    {
        return $this->delayedRequestWarning;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $labelData = $data['label'] ?? null;
        $label = is_array($labelData) ? LabelDetails::fromArray($labelData) : null;

        return new self(
            waybillNumber: (string) ($labelData['shipmentNumber'] ?? $labelData['waybillNumber'] ?? ''),
            label: $label,
            blockingPaymentUrl: isset($data['blockingPaymentURL']) ? (string) $data['blockingPaymentURL'] : null,
            courierRequestId: isset($data['courierRequestID']) ? (string) $data['courierRequestID'] : null,
            payAfterAcceptIgnored: isset($data['payAfterAcceptIgnored'])
                ? (bool) $data['payAfterAcceptIgnored'] : null,
            delayedDeliveryWarning: (string) ($data['delayedDeliveryWarning'] ?? ''),
            delayedRequestWarning: (string) ($data['delayedRequestWarning'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label?->toArray(),
            'blockingPaymentURL' => $this->blockingPaymentUrl,
            'courierRequestID' => $this->courierRequestId,
            'payAfterAcceptIgnored' => $this->payAfterAcceptIgnored,
            'delayedDeliveryWarning' => $this->delayedDeliveryWarning,
            'delayedRequestWarning' => $this->delayedRequestWarning,
        ];
    }
}
