<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Enum\ShipmentType;
use Econt\EcontApi\Model\Location\Address;

/**
 * Courier pick-up request payload.
 */
class CourierRequest
{
    public function __construct(
        private readonly ClientProfile $senderClient,
        private readonly Address $senderAddress,
        private readonly string $fromTime,
        private readonly string $toTime,
        private readonly ?ShipmentType $shipmentType = null,
        private readonly ?float $weight = null,
    ) {
    }

    public function getSenderClient(): ClientProfile
    {
        return $this->senderClient;
    }

    public function getSenderAddress(): Address
    {
        return $this->senderAddress;
    }

    public function getFromTime(): string
    {
        return $this->fromTime;
    }

    public function getToTime(): string
    {
        return $this->toTime;
    }

    public function getShipmentType(): ?ShipmentType
    {
        return $this->shipmentType;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $shipmentType = null;
        if (isset($data['shipmentType'])) {
            $shipmentType = ShipmentType::from(strtolower((string) $data['shipmentType']));
        }

        return new self(
            senderClient: ClientProfile::fromArray($data['senderClient'] ?? []),
            senderAddress: Address::fromArray($data['senderAddress'] ?? []),
            fromTime: (string) ($data['fromTime'] ?? ''),
            toTime: (string) ($data['toTime'] ?? ''),
            shipmentType: $shipmentType,
            weight: isset($data['weight']) ? (float) $data['weight'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'senderClient' => $this->senderClient->toArray(),
            'senderAddress' => $this->senderAddress->toArray(),
            'fromTime' => $this->fromTime,
            'toTime' => $this->toTime,
            'shipmentType' => $this->shipmentType?->value,
            'weight' => $this->weight,
        ];
    }
}
