<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Full label object returned by createLabel / validateLabel / updateLabel.
 * Every field from the Econt API response is mapped here.
 */
class LabelDetails
{
    /**
     * @param LabelServiceRecord[] $services
     * @param array<int, mixed>    $nextShipments
     * @param array<int, mixed>    $trackingEvents
     */
    public function __construct(
        public readonly string $shipmentNumber,
        public readonly ?string $storageOfficeName,
        public readonly ?string $storagePersonName,
        public readonly ?int $createdTime,
        public readonly ?int $sendTime,
        public readonly ?int $deliveryTime,
        public readonly string $shipmentType,
        public readonly int $packCount,
        public readonly string $shipmentDescription,
        public readonly float $weight,
        public readonly ?string $senderDeliveryType,
        public readonly ?string $senderOfficeCode,
        public readonly ?string $receiverDeliveryType,
        public readonly ?string $receiverOfficeCode,
        public readonly string $hubCode,
        public readonly string $hubName,
        public readonly string $hubNameEn,
        public readonly float $cdCollectedAmount,
        public readonly string $cdCollectedCurrency,
        public readonly ?int $cdCollectedTime,
        public readonly float $cdPaidAmount,
        public readonly string $cdPaidCurrency,
        public readonly ?int $cdPaidTime,
        public readonly float $totalPrice,
        public readonly string $currency,
        public readonly float $discountPercent,
        public readonly float $discountAmount,
        public readonly string $discountDescription,
        public readonly float $senderDueAmount,
        public readonly float $receiverDueAmount,
        public readonly float $otherDueAmount,
        public readonly int $deliveryAttemptCount,
        public readonly string $previousShipmentNumber,
        public readonly array $services,
        public readonly ?string $pdfUrl,
        public readonly ?int $expectedDeliveryDate,
        public readonly ?string $returnShipmentUrl,
        public readonly ?string $rejectOriginalParcelPaySide,
        public readonly ?string $rejectReturnParcelPaySide,
        public readonly ?ShipmentEdition $shipmentEdition,
        public readonly array $nextShipments,
        public readonly array $trackingEvents,
        public readonly ?string $lastProcessedInstruction,
        public readonly string $warnings,
        public readonly ?string $shortDeliveryStatus,
        public readonly ?string $shortDeliveryStatusEn,
        public readonly string $routingCode,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $services = [];
        foreach ((array) ($data['services'] ?? []) as $s) {
            if (is_array($s)) {
                $services[] = LabelServiceRecord::fromArray($s);
            }
        }

        return new self(
            shipmentNumber: (string) ($data['shipmentNumber'] ?? ''),
            storageOfficeName: isset($data['storageOfficeName']) ? (string) $data['storageOfficeName'] : null,
            storagePersonName: isset($data['storagePersonName']) ? (string) $data['storagePersonName'] : null,
            createdTime: isset($data['createdTime']) ? (int) $data['createdTime'] : null,
            sendTime: isset($data['sendTime']) ? (int) $data['sendTime'] : null,
            deliveryTime: isset($data['deliveryTime']) ? (int) $data['deliveryTime'] : null,
            shipmentType: (string) ($data['shipmentType'] ?? ''),
            packCount: (int) ($data['packCount'] ?? 1),
            shipmentDescription: (string) ($data['shipmentDescription'] ?? ''),
            weight: (float) ($data['weight'] ?? 0.0),
            senderDeliveryType: isset($data['senderDeliveryType']) ? (string) $data['senderDeliveryType'] : null,
            senderOfficeCode: isset($data['senderOfficeCode']) ? (string) $data['senderOfficeCode'] : null,
            receiverDeliveryType: isset($data['receiverDeliveryType']) ? (string) $data['receiverDeliveryType'] : null,
            receiverOfficeCode: isset($data['receiverOfficeCode']) ? (string) $data['receiverOfficeCode'] : null,
            hubCode: (string) ($data['hubCode'] ?? ''),
            hubName: (string) ($data['hubName'] ?? ''),
            hubNameEn: (string) ($data['hubNameEN'] ?? ''),
            cdCollectedAmount: (float) ($data['cdCollectedAmount'] ?? 0.0),
            cdCollectedCurrency: (string) ($data['cdCollectedCurrency'] ?? ''),
            cdCollectedTime: isset($data['cdCollectedTime']) ? (int) $data['cdCollectedTime'] : null,
            cdPaidAmount: (float) ($data['cdPaidAmount'] ?? 0.0),
            cdPaidCurrency: (string) ($data['cdPaidCurrency'] ?? ''),
            cdPaidTime: isset($data['cdPaidTime']) ? (int) $data['cdPaidTime'] : null,
            totalPrice: (float) ($data['totalPrice'] ?? 0.0),
            currency: (string) ($data['currency'] ?? ''),
            discountPercent: (float) ($data['discountPercent'] ?? 0.0),
            discountAmount: (float) ($data['discountAmount'] ?? 0.0),
            discountDescription: (string) ($data['discountDescription'] ?? ''),
            senderDueAmount: (float) ($data['senderDueAmount'] ?? 0.0),
            receiverDueAmount: (float) ($data['receiverDueAmount'] ?? 0.0),
            otherDueAmount: (float) ($data['otherDueAmount'] ?? 0.0),
            deliveryAttemptCount: (int) ($data['deliveryAttemptCount'] ?? 0),
            previousShipmentNumber: (string) ($data['previousShipmentNumber'] ?? ''),
            services: $services,
            pdfUrl: isset($data['pdfURL']) ? (string) $data['pdfURL'] : null,
            expectedDeliveryDate: isset($data['expectedDeliveryDate']) ? (int) $data['expectedDeliveryDate'] : null,
            returnShipmentUrl: isset($data['returnShipmentURL']) ? (string) $data['returnShipmentURL'] : null,
            rejectOriginalParcelPaySide: isset($data['rejectOriginalParcelPaySide'])
                ? (string) $data['rejectOriginalParcelPaySide'] : null,
            rejectReturnParcelPaySide: isset($data['rejectReturnParcelPaySide'])
                ? (string) $data['rejectReturnParcelPaySide'] : null,
            shipmentEdition: isset($data['shipmentEdition']) && is_array($data['shipmentEdition'])
                ? ShipmentEdition::fromArray($data['shipmentEdition']) : null,
            nextShipments: (array) ($data['nextShipments'] ?? []),
            trackingEvents: (array) ($data['trackingEvents'] ?? []),
            lastProcessedInstruction: isset($data['lastProcessedInstruction'])
                ? (string) $data['lastProcessedInstruction'] : null,
            warnings: (string) ($data['warnings'] ?? ''),
            shortDeliveryStatus: isset($data['shortDeliveryStatus']) ? (string) $data['shortDeliveryStatus'] : null,
            shortDeliveryStatusEn: isset($data['shortDeliveryStatusEn'])
                ? (string) $data['shortDeliveryStatusEn'] : null,
            routingCode: (string) ($data['routingCode'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'shipmentNumber' => $this->shipmentNumber,
            'storageOfficeName' => $this->storageOfficeName,
            'storagePersonName' => $this->storagePersonName,
            'createdTime' => $this->createdTime,
            'sendTime' => $this->sendTime,
            'deliveryTime' => $this->deliveryTime,
            'shipmentType' => $this->shipmentType,
            'packCount' => $this->packCount,
            'shipmentDescription' => $this->shipmentDescription,
            'weight' => $this->weight,
            'senderDeliveryType' => $this->senderDeliveryType,
            'senderOfficeCode' => $this->senderOfficeCode,
            'receiverDeliveryType' => $this->receiverDeliveryType,
            'receiverOfficeCode' => $this->receiverOfficeCode,
            'hubCode' => $this->hubCode,
            'hubName' => $this->hubName,
            'hubNameEN' => $this->hubNameEn,
            'cdCollectedAmount' => $this->cdCollectedAmount,
            'cdCollectedCurrency' => $this->cdCollectedCurrency,
            'cdCollectedTime' => $this->cdCollectedTime,
            'cdPaidAmount' => $this->cdPaidAmount,
            'cdPaidCurrency' => $this->cdPaidCurrency,
            'cdPaidTime' => $this->cdPaidTime,
            'totalPrice' => $this->totalPrice,
            'currency' => $this->currency,
            'discountPercent' => $this->discountPercent,
            'discountAmount' => $this->discountAmount,
            'discountDescription' => $this->discountDescription,
            'senderDueAmount' => $this->senderDueAmount,
            'receiverDueAmount' => $this->receiverDueAmount,
            'otherDueAmount' => $this->otherDueAmount,
            'deliveryAttemptCount' => $this->deliveryAttemptCount,
            'previousShipmentNumber' => $this->previousShipmentNumber,
            'services' => array_map(fn(LabelServiceRecord $s) => $s->toArray(), $this->services),
            'pdfURL' => $this->pdfUrl,
            'expectedDeliveryDate' => $this->expectedDeliveryDate,
            'returnShipmentURL' => $this->returnShipmentUrl,
            'rejectOriginalParcelPaySide' => $this->rejectOriginalParcelPaySide,
            'rejectReturnParcelPaySide' => $this->rejectReturnParcelPaySide,
            'shipmentEdition' => $this->shipmentEdition?->toArray(),
            'nextShipments' => $this->nextShipments,
            'trackingEvents' => $this->trackingEvents,
            'lastProcessedInstruction' => $this->lastProcessedInstruction,
            'warnings' => $this->warnings,
            'shortDeliveryStatus' => $this->shortDeliveryStatus,
            'shortDeliveryStatusEn' => $this->shortDeliveryStatusEn,
            'routingCode' => $this->routingCode,
        ];
    }
}

