<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Enum\ShipmentType;
use Econt\EcontApi\Enum\TariffSubCode;
use Econt\EcontApi\Exception\EcontValidationException;
use Econt\EcontApi\Model\Location\Address;

/**
 * Shipping label – the central entity for creating, validating, and pricing shipments.
 *
 * Delivery routing is controlled by a combination of address / office code fields:
 *   - Door-to-door:   senderAddress + receiverAddress  (tariffSubCode: DOOR_DOOR)
 *   - Door-to-office: senderAddress + receiverOfficeCode  (tariffSubCode: DOOR_OFFICE)
 *   - Office-to-door: senderOfficeCode + receiverAddress  (tariffSubCode: OFFICE_DOOR)
 *   - Office-to-office: senderOfficeCode + receiverOfficeCode  (tariffSubCode: OFFICE_OFFICE)
 */
class ShippingLabel
{
    /**
     * @param PackingListElement[]|null $packingList
     * @param Instruction[]|null        $instructions
     */
    public function __construct(
        private readonly ClientProfile $senderClient,
        private readonly ClientProfile $receiverClient,
        private readonly int $packCount,
        private readonly ShipmentType $shipmentType,
        private readonly float $weight,
        private readonly ?Address $senderAddress = null,
        private readonly ?string $senderOfficeCode = null,
        private readonly ?Address $receiverAddress = null,
        private readonly ?string $receiverOfficeCode = null,
        private readonly ?TariffSubCode $tariffSubCode = null,
        private string $mode = 'create',
        private readonly ?int $envelopeNumbers = null,
        private readonly ?bool $sizeUnder60cm = null,
        private readonly ?float $shipmentDimensionsL = null,
        private readonly ?float $shipmentDimensionsW = null,
        private readonly ?float $shipmentDimensionsH = null,
        private readonly ?string $shipmentDescription = null,
        private readonly ?string $orderNumber = null,
        private readonly ?string $sendDate = null,
        private readonly ?string $holidayDeliveryDay = null,
        private readonly ?bool $keepUpright = null,
        private readonly ?bool $payAfterAccept = null,
        private readonly ?bool $payAfterTest = null,
        private readonly ?bool $partialDelivery = null,
        private readonly ?string $packingListType = null,
        private readonly ?array $packingList = null,
        private readonly ?ShippingLabelServices $services = null,
        private readonly ?ReturnInstructionParams $returnInstructions = null,
        private readonly ?array $instructions = null,
    ) {
    }

    public function getSenderClient(): ClientProfile
    {
        return $this->senderClient;
    }

    public function getReceiverClient(): ClientProfile
    {
        return $this->receiverClient;
    }

    public function getSenderAddress(): ?Address
    {
        return $this->senderAddress;
    }

    public function getSenderOfficeCode(): ?string
    {
        return $this->senderOfficeCode;
    }

    public function getReceiverAddress(): ?Address
    {
        return $this->receiverAddress;
    }

    public function getReceiverOfficeCode(): ?string
    {
        return $this->receiverOfficeCode;
    }

    public function getTariffSubCode(): ?TariffSubCode
    {
        return $this->tariffSubCode;
    }

    public function getPackCount(): int
    {
        return $this->packCount;
    }

    public function getShipmentType(): ShipmentType
    {
        return $this->shipmentType;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $clone = clone $this;
        $clone->mode = $mode;

        return $clone;
    }

    public function getEnvelopeNumbers(): ?int
    {
        return $this->envelopeNumbers;
    }

    public function getSizeUnder60cm(): ?bool
    {
        return $this->sizeUnder60cm;
    }

    public function getShipmentDimensionsL(): ?float
    {
        return $this->shipmentDimensionsL;
    }

    public function getShipmentDimensionsW(): ?float
    {
        return $this->shipmentDimensionsW;
    }

    public function getShipmentDimensionsH(): ?float
    {
        return $this->shipmentDimensionsH;
    }

    public function getShipmentDescription(): ?string
    {
        return $this->shipmentDescription;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function getSendDate(): ?string
    {
        return $this->sendDate;
    }

    public function getHolidayDeliveryDay(): ?string
    {
        return $this->holidayDeliveryDay;
    }

    public function getKeepUpright(): ?bool
    {
        return $this->keepUpright;
    }

    public function getPayAfterAccept(): ?bool
    {
        return $this->payAfterAccept;
    }

    public function getPayAfterTest(): ?bool
    {
        return $this->payAfterTest;
    }

    public function getPartialDelivery(): ?bool
    {
        return $this->partialDelivery;
    }

    public function getPackingListType(): ?string
    {
        return $this->packingListType;
    }

    /**
     * @return PackingListElement[]|null
     */
    public function getPackingList(): ?array
    {
        return $this->packingList;
    }

    public function getServices(): ?ShippingLabelServices
    {
        return $this->services;
    }

    public function getReturnInstructions(): ?ReturnInstructionParams
    {
        return $this->returnInstructions;
    }

    /**
     * @return Instruction[]|null
     */
    public function getInstructions(): ?array
    {
        return $this->instructions;
    }

    /**
     * Validate the label business rules before making an HTTP call.
     *
     * @throws EcontValidationException
     */
    public function validate(): void
    {
        $violations = [];

        if ($this->packCount < 1) {
            $violations[] = 'packCount must be >= 1';
        }

        if ($this->weight <= 0) {
            $violations[] = 'weight must be > 0';
        }

        if ($this->senderAddress === null && $this->senderOfficeCode === null) {
            $violations[] = 'Either senderAddress or senderOfficeCode must be provided';
        }

        if ($this->receiverAddress === null && $this->receiverOfficeCode === null) {
            $violations[] = 'Either receiverAddress or receiverOfficeCode must be provided';
        }

        if ($this->shipmentType === ShipmentType::POST_PACK && $this->sizeUnder60cm !== true) {
            $violations[] = 'sizeUnder60cm must be true for POST_PACK shipment type';
        }

        if ($this->partialDelivery === true && $this->returnInstructions === null) {
            $violations[] = 'returnInstructions must be set when partialDelivery is true';
        }

        if (!empty($violations)) {
            throw new EcontValidationException(
                'Shipping label validation failed: ' . implode('; ', $violations),
                $violations,
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $labelData = $data['label'] ?? $data;

        /** @var PackingListElement[]|null $packingList */
        $packingList = null;
        if (isset($labelData['packingList']) && is_array($labelData['packingList'])) {
            $packingList = array_map(
                static fn (array $item): PackingListElement => PackingListElement::fromArray($item),
                $labelData['packingList'],
            );
        }

        /** @var Instruction[]|null $instructions */
        $instructions = null;
        if (isset($labelData['instructions']) && is_array($labelData['instructions'])) {
            $instructions = array_map(
                static fn (array $item): Instruction => Instruction::fromArray($item),
                $labelData['instructions'],
            );
        }

        $shipmentTypeRaw = strtolower((string) ($labelData['shipmentType'] ?? 'pack'));
        $shipmentType = ShipmentType::from($shipmentTypeRaw);

        $tariffSubCode = null;
        if (isset($labelData['tariffSubCode'])) {
            $tariffSubCode = TariffSubCode::from((string) $labelData['tariffSubCode']);
        }

        return new self(
            senderClient: ClientProfile::fromArray($labelData['senderClient'] ?? []),
            receiverClient: ClientProfile::fromArray($labelData['receiverClient'] ?? []),
            packCount: (int) ($labelData['packCount'] ?? 1),
            shipmentType: $shipmentType,
            weight: (float) ($labelData['weight'] ?? 0.0),
            senderAddress: isset($labelData['senderAddress']) && is_array($labelData['senderAddress'])
                ? Address::fromArray($labelData['senderAddress']) : null,
            senderOfficeCode: isset($labelData['senderOfficeCode'])
                ? (string) $labelData['senderOfficeCode'] : null,
            receiverAddress: isset($labelData['receiverAddress']) && is_array($labelData['receiverAddress'])
                ? Address::fromArray($labelData['receiverAddress']) : null,
            receiverOfficeCode: isset($labelData['receiverOfficeCode'])
                ? (string) $labelData['receiverOfficeCode'] : null,
            tariffSubCode: $tariffSubCode,
            mode: (string) ($data['mode'] ?? $labelData['mode'] ?? 'create'),
            envelopeNumbers: isset($labelData['envelopeNumbers']) ? (int) $labelData['envelopeNumbers'] : null,
            sizeUnder60cm: isset($labelData['sizeUnder60cm']) ? (bool) $labelData['sizeUnder60cm'] : null,
            shipmentDimensionsL: isset($labelData['shipmentDimensionsL'])
                ? (float) $labelData['shipmentDimensionsL'] : null,
            shipmentDimensionsW: isset($labelData['shipmentDimensionsW'])
                ? (float) $labelData['shipmentDimensionsW'] : null,
            shipmentDimensionsH: isset($labelData['shipmentDimensionsH'])
                ? (float) $labelData['shipmentDimensionsH'] : null,
            shipmentDescription: isset($labelData['shipmentDescription'])
                ? (string) $labelData['shipmentDescription'] : null,
            orderNumber: isset($labelData['orderNumber']) ? (string) $labelData['orderNumber'] : null,
            sendDate: isset($labelData['sendDate']) ? (string) $labelData['sendDate'] : null,
            holidayDeliveryDay: isset($labelData['holidayDeliveryDay'])
                ? (string) $labelData['holidayDeliveryDay'] : null,
            keepUpright: isset($labelData['keepUpright']) ? (bool) $labelData['keepUpright'] : null,
            payAfterAccept: isset($labelData['payAfterAccept']) ? (bool) $labelData['payAfterAccept'] : null,
            payAfterTest: isset($labelData['payAfterTest']) ? (bool) $labelData['payAfterTest'] : null,
            partialDelivery: isset($labelData['partialDelivery']) ? (bool) $labelData['partialDelivery'] : null,
            packingListType: isset($labelData['packingListType']) ? (string) $labelData['packingListType'] : null,
            packingList: $packingList,
            services: isset($labelData['services']) && is_array($labelData['services'])
                ? ShippingLabelServices::fromArray($labelData['services']) : null,
            returnInstructions: isset($labelData['returnInstructions'])
                && is_array($labelData['returnInstructions'])
                ? ReturnInstructionParams::fromArray($labelData['returnInstructions']) : null,
            instructions: $instructions,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'senderClient' => $this->senderClient->toArray(),
            'senderAddress' => $this->senderAddress?->toArray(),
            'senderOfficeCode' => $this->senderOfficeCode,
            'receiverClient' => $this->receiverClient->toArray(),
            'receiverAddress' => $this->receiverAddress?->toArray(),
            'receiverOfficeCode' => $this->receiverOfficeCode,
            'tariffSubCode' => $this->tariffSubCode?->value,
            'packCount' => $this->packCount,
            'shipmentType' => $this->shipmentType->value,
            'weight' => $this->weight,
            'mode' => $this->mode,
            'envelopeNumbers' => $this->envelopeNumbers,
            'sizeUnder60cm' => $this->sizeUnder60cm,
            'shipmentDimensionsL' => $this->shipmentDimensionsL,
            'shipmentDimensionsW' => $this->shipmentDimensionsW,
            'shipmentDimensionsH' => $this->shipmentDimensionsH,
            'shipmentDescription' => $this->shipmentDescription,
            'orderNumber' => $this->orderNumber,
            'sendDate' => $this->sendDate,
            'holidayDeliveryDay' => $this->holidayDeliveryDay,
            'keepUpright' => $this->keepUpright,
            'payAfterAccept' => $this->payAfterAccept,
            'payAfterTest' => $this->payAfterTest,
            'partialDelivery' => $this->partialDelivery,
            'packingListType' => $this->packingListType,
            'packingList' => $this->packingList !== null
                ? array_map(static fn (PackingListElement $e): array => $e->toArray(), $this->packingList)
                : null,
            'services' => $this->services?->toArray(),
            'returnInstructions' => $this->returnInstructions?->toArray(),
            'instructions' => $this->instructions !== null
                ? array_map(static fn (Instruction $i): array => $i->toArray(), $this->instructions)
                : null,
        ];
    }
}
