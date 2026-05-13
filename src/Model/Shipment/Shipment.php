<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Model\AbstractModel;

class Shipment extends AbstractModel
{
    private ?string $shipmentId;
    private ?string $invoiceId;
    private ?string $externalShipmentId;
    private ?string $guid;
    private ?ShipmentParty $sender;
    private ?ShipmentParty $receiver;
    private ?string $payment;
    private ?float $declaredValue;
    private ?string $currency;
    private ?string $weight;
    private ?string $description;
    private ?array $items;
    private ?string $loadType;
    private ?string $service;
    private ?string $serviceType;
    private ?bool $cod;
    private ?float $codValue;
    private ?string $codCurrency;
    private ?string $invoiceBeforeDelivery;
    private ?string $payAfterAccept;
    private ?string $payAfterTest;
    private ?string $smsNotification;
    private ?string $emailNotification;
    private ?string $deliverySaturday;
    private ?string $priorService;
    private ?string $priorTime;
    private ?string $instructions;
    private ?string $instructionsForCourier;
    private ?string $scheduleDate;
    private ?string $scheduleTimeFrom;
    private ?string $scheduleTimeTo;
    private ?string $prepayment;

    public function __construct(
        ?string $shipmentId = null,
        ?string $invoiceId = null,
        ?string $externalShipmentId = null,
        ?string $guid = null,
        ?ShipmentParty $sender = null,
        ?ShipmentParty $receiver = null,
        ?string $payment = null,
        ?float $declaredValue = null,
        ?string $currency = null,
        ?string $weight = null,
        ?string $description = null,
        ?array $items = null,
        ?string $loadType = null,
        ?string $service = null,
        ?string $serviceType = null,
        ?bool $cod = null,
        ?float $codValue = null,
        ?string $codCurrency = null,
        ?string $invoiceBeforeDelivery = null,
        ?string $payAfterAccept = null,
        ?string $payAfterTest = null,
        ?string $smsNotification = null,
        ?string $emailNotification = null,
        ?string $deliverySaturday = null,
        ?string $priorService = null,
        ?string $priorTime = null,
        ?string $instructions = null,
        ?string $instructionsForCourier = null,
        ?string $scheduleDate = null,
        ?string $scheduleTimeFrom = null,
        ?string $scheduleTimeTo = null,
        ?string $prepayment = null
    ) {
        $this->shipmentId = $shipmentId;
        $this->invoiceId = $invoiceId;
        $this->externalShipmentId = $externalShipmentId;
        $this->guid = $guid;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->payment = $payment;
        $this->declaredValue = $declaredValue;
        $this->currency = $currency;
        $this->weight = $weight;
        $this->description = $description;
        $this->items = $items;
        $this->loadType = $loadType;
        $this->service = $service;
        $this->serviceType = $serviceType;
        $this->cod = $cod;
        $this->codValue = $codValue;
        $this->codCurrency = $codCurrency;
        $this->invoiceBeforeDelivery = $invoiceBeforeDelivery;
        $this->payAfterAccept = $payAfterAccept;
        $this->payAfterTest = $payAfterTest;
        $this->smsNotification = $smsNotification;
        $this->emailNotification = $emailNotification;
        $this->deliverySaturday = $deliverySaturday;
        $this->priorService = $priorService;
        $this->priorTime = $priorTime;
        $this->instructions = $instructions;
        $this->instructionsForCourier = $instructionsForCourier;
        $this->scheduleDate = $scheduleDate;
        $this->scheduleTimeFrom = $scheduleTimeFrom;
        $this->scheduleTimeTo = $scheduleTimeTo;
        $this->prepayment = $prepayment;
    }

    public function getShipmentId(): ?string
    {
        return $this->shipmentId;
    }

    public function setShipmentId(?string $shipmentId): self
    {
        $this->shipmentId = $shipmentId;
        return $this;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(?string $invoiceId): self
    {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    public function getExternalShipmentId(): ?string
    {
        return $this->externalShipmentId;
    }

    public function setExternalShipmentId(?string $externalShipmentId): self
    {
        $this->externalShipmentId = $externalShipmentId;
        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;
        return $this;
    }

    public function getSender(): ?ShipmentParty
    {
        return $this->sender;
    }

    public function setSender(?ShipmentParty $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getReceiver(): ?ShipmentParty
    {
        return $this->receiver;
    }

    public function setReceiver(?ShipmentParty $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    public function getDeclaredValue(): ?float
    {
        return $this->declaredValue;
    }

    public function setDeclaredValue(?float $declaredValue): self
    {
        $this->declaredValue = $declaredValue;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function getLoadType(): ?string
    {
        return $this->loadType;
    }

    public function setLoadType(?string $loadType): self
    {
        $this->loadType = $loadType;
        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;
        return $this;
    }

    public function getServiceType(): ?string
    {
        return $this->serviceType;
    }

    public function setServiceType(?string $serviceType): self
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    public function isCod(): ?bool
    {
        return $this->cod;
    }

    public function setIsCod(?bool $cod): self
    {
        $this->cod = $cod;
        return $this;
    }

    public function getCodValue(): ?float
    {
        return $this->codValue;
    }

    public function setCodValue(?float $codValue): self
    {
        $this->codValue = $codValue;
        return $this;
    }

    public function getCodCurrency(): ?string
    {
        return $this->codCurrency;
    }

    public function setCodCurrency(?string $codCurrency): self
    {
        $this->codCurrency = $codCurrency;
        return $this;
    }

    public function getInvoiceBeforeDelivery(): ?string
    {
        return $this->invoiceBeforeDelivery;
    }

    public function setInvoiceBeforeDelivery(?string $invoiceBeforeDelivery): self
    {
        $this->invoiceBeforeDelivery = $invoiceBeforeDelivery;
        return $this;
    }

    public function getPayAfterAccept(): ?string
    {
        return $this->payAfterAccept;
    }

    public function setPayAfterAccept(?string $payAfterAccept): self
    {
        $this->payAfterAccept = $payAfterAccept;
        return $this;
    }

    public function getPayAfterTest(): ?string
    {
        return $this->payAfterTest;
    }

    public function setPayAfterTest(?string $payAfterTest): self
    {
        $this->payAfterTest = $payAfterTest;
        return $this;
    }

    public function getSmsNotification(): ?string
    {
        return $this->smsNotification;
    }

    public function setSmsNotification(?string $smsNotification): self
    {
        $this->smsNotification = $smsNotification;
        return $this;
    }

    public function getEmailNotification(): ?string
    {
        return $this->emailNotification;
    }

    public function setEmailNotification(?string $emailNotification): self
    {
        $this->emailNotification = $emailNotification;
        return $this;
    }

    public function getDeliverySaturday(): ?string
    {
        return $this->deliverySaturday;
    }

    public function setDeliverySaturday(?string $deliverySaturday): self
    {
        $this->deliverySaturday = $deliverySaturday;
        return $this;
    }

    public function getPriorService(): ?string
    {
        return $this->priorService;
    }

    public function setPriorService(?string $priorService): self
    {
        $this->priorService = $priorService;
        return $this;
    }

    public function getPriorTime(): ?string
    {
        return $this->priorTime;
    }

    public function setPriorTime(?string $priorTime): self
    {
        $this->priorTime = $priorTime;
        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): self
    {
        $this->instructions = $instructions;
        return $this;
    }

    public function getInstructionsForCourier(): ?string
    {
        return $this->instructionsForCourier;
    }

    public function setInstructionsForCourier(?string $instructionsForCourier): self
    {
        $this->instructionsForCourier = $instructionsForCourier;
        return $this;
    }

    public function getScheduleDate(): ?string
    {
        return $this->scheduleDate;
    }

    public function setScheduleDate(?string $scheduleDate): self
    {
        $this->scheduleDate = $scheduleDate;
        return $this;
    }

    public function getScheduleTimeFrom(): ?string
    {
        return $this->scheduleTimeFrom;
    }

    public function setScheduleTimeFrom(?string $scheduleTimeFrom): self
    {
        $this->scheduleTimeFrom = $scheduleTimeFrom;
        return $this;
    }

    public function getScheduleTimeTo(): ?string
    {
        return $this->scheduleTimeTo;
    }

    public function setScheduleTimeTo(?string $scheduleTimeTo): self
    {
        $this->scheduleTimeTo = $scheduleTimeTo;
        return $this;
    }

    public function getPrepayment(): ?string
    {
        return $this->prepayment;
    }

    public function setPrepayment(?string $prepayment): self
    {
        $this->prepayment = $prepayment;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $sender = null;
        if (isset($data['sender']) && is_array($data['sender'])) {
            $sender = ShipmentParty::fromArray($data['sender']);
        }

        $receiver = null;
        if (isset($data['receiver']) && is_array($data['receiver'])) {
            $receiver = ShipmentParty::fromArray($data['receiver']);
        }

        $items = null;
        if (isset($data['items']) && is_array($data['items'])) {
            $items = array_map(
                fn($item) => ShipmentItem::fromArray($item),
                $data['items']
            );
        }

        return new self(
            $data['shipmentId'] ?? null,
            $data['invoiceId'] ?? null,
            $data['externalShipmentId'] ?? null,
            $data['guid'] ?? null,
            $sender,
            $receiver,
            $data['payment'] ?? null,
            isset($data['declaredValue']) ? (float) $data['declaredValue'] : null,
            $data['currency'] ?? null,
            $data['weight'] ?? null,
            $data['description'] ?? null,
            $items,
            $data['loadType'] ?? null,
            $data['service'] ?? null,
            $data['serviceType'] ?? null,
            $data['cod'] ?? null,
            isset($data['codValue']) ? (float) $data['codValue'] : null,
            $data['codCurrency'] ?? null,
            $data['invoiceBeforeDelivery'] ?? null,
            $data['payAfterAccept'] ?? null,
            $data['payAfterTest'] ?? null,
            $data['smsNotification'] ?? null,
            $data['emailNotification'] ?? null,
            $data['deliverySaturday'] ?? null,
            $data['priorService'] ?? null,
            $data['priorTime'] ?? null,
            $data['instructions'] ?? null,
            $data['instructionsForCourier'] ?? null,
            $data['scheduleDate'] ?? null,
            $data['scheduleTimeFrom'] ?? null,
            $data['scheduleTimeTo'] ?? null,
            $data['prepayment'] ?? null
        );
    }
}