<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Result of trackShipment (getWaybillContents) operation.
 */
class ShipmentTrackingResult
{
    /**
     * @param array<mixed>         $events
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        private readonly string $waybillNumber,
        private readonly array $events,
        private readonly array $rawResponse,
    ) {
    }

    public function getWaybillNumber(): string
    {
        return $this->waybillNumber;
    }

    /**
     * @return array<mixed>
     */
    public function getEvents(): array
    {
        return $this->events;
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
        return new self(
            waybillNumber: (string) ($data['waybillNumber'] ?? $data['shipmentNumber'] ?? ''),
            events: (array) ($data['events'] ?? $data['shipmentStatuses'] ?? []),
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
            'events' => $this->events,
        ];
    }
}
