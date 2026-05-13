<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Model\Location\Address;

/**
 * Return instructions when partial delivery or rejection is configured.
 */
class ReturnInstructionParams
{
    public function __construct(
        private readonly ?string $returnParcelDestination = null,
        private readonly ?bool $returnParcelIsDocument = null,
        private readonly ?int $daysUntilReturn = null,
        private readonly ?string $returnParcelPaymentSide = null,
        private readonly ?string $rejectAction = null,
        private readonly ?string $rejectInstruction = null,
        private readonly ?string $rejectContact = null,
        private readonly ?ClientProfile $rejectReturnClient = null,
        private readonly ?string $rejectReturnAgent = null,
        private readonly ?string $rejectReturnOfficeCode = null,
        private readonly ?Address $rejectReturnAddress = null,
        private readonly ?string $rejectOriginalParcelPaySide = null,
        private readonly ?string $rejectReturnParcelPaySide = null,
        private readonly ?bool $printReturnParcel = null,
        private readonly ?bool $signatureDocuments = null,
        private readonly ?string $signaturePenColor = null,
        private readonly ?int $signatureCount = null,
        private readonly ?string $signaturePageNumbers = null,
        private readonly ?string $signatureOtherInstructions = null,
    ) {
    }

    public function getReturnParcelDestination(): ?string
    {
        return $this->returnParcelDestination;
    }

    public function getReturnParcelIsDocument(): ?bool
    {
        return $this->returnParcelIsDocument;
    }

    public function getDaysUntilReturn(): ?int
    {
        return $this->daysUntilReturn;
    }

    public function getReturnParcelPaymentSide(): ?string
    {
        return $this->returnParcelPaymentSide;
    }

    public function getRejectAction(): ?string
    {
        return $this->rejectAction;
    }

    public function getRejectInstruction(): ?string
    {
        return $this->rejectInstruction;
    }

    public function getRejectContact(): ?string
    {
        return $this->rejectContact;
    }

    public function getRejectReturnClient(): ?ClientProfile
    {
        return $this->rejectReturnClient;
    }

    public function getRejectReturnAgent(): ?string
    {
        return $this->rejectReturnAgent;
    }

    public function getRejectReturnOfficeCode(): ?string
    {
        return $this->rejectReturnOfficeCode;
    }

    public function getRejectReturnAddress(): ?Address
    {
        return $this->rejectReturnAddress;
    }

    public function getRejectOriginalParcelPaySide(): ?string
    {
        return $this->rejectOriginalParcelPaySide;
    }

    public function getRejectReturnParcelPaySide(): ?string
    {
        return $this->rejectReturnParcelPaySide;
    }

    public function getPrintReturnParcel(): ?bool
    {
        return $this->printReturnParcel;
    }

    public function getSignatureDocuments(): ?bool
    {
        return $this->signatureDocuments;
    }

    public function getSignaturePenColor(): ?string
    {
        return $this->signaturePenColor;
    }

    public function getSignatureCount(): ?int
    {
        return $this->signatureCount;
    }

    public function getSignaturePageNumbers(): ?string
    {
        return $this->signaturePageNumbers;
    }

    public function getSignatureOtherInstructions(): ?string
    {
        return $this->signatureOtherInstructions;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            returnParcelDestination: isset($data['returnParcelDestination'])
                ? (string) $data['returnParcelDestination']
                : null,
            returnParcelIsDocument: isset($data['returnParcelIsDocument'])
                ? (bool) $data['returnParcelIsDocument']
                : null,
            daysUntilReturn: isset($data['daysUntilReturn']) ? (int) $data['daysUntilReturn'] : null,
            returnParcelPaymentSide: isset($data['returnParcelPaymentSide'])
                ? (string) $data['returnParcelPaymentSide']
                : null,
            rejectAction: isset($data['rejectAction']) ? (string) $data['rejectAction'] : null,
            rejectInstruction: isset($data['rejectInstruction']) ? (string) $data['rejectInstruction'] : null,
            rejectContact: isset($data['rejectContact']) ? (string) $data['rejectContact'] : null,
            rejectReturnClient: isset($data['rejectReturnClient']) && is_array($data['rejectReturnClient'])
                ? ClientProfile::fromArray($data['rejectReturnClient'])
                : null,
            rejectReturnAgent: isset($data['rejectReturnAgent']) ? (string) $data['rejectReturnAgent'] : null,
            rejectReturnOfficeCode: isset($data['rejectReturnOfficeCode'])
                ? (string) $data['rejectReturnOfficeCode']
                : null,
            rejectReturnAddress: isset($data['rejectReturnAddress']) && is_array($data['rejectReturnAddress'])
                ? Address::fromArray($data['rejectReturnAddress'])
                : null,
            rejectOriginalParcelPaySide: isset($data['rejectOriginalParcelPaySide'])
                ? (string) $data['rejectOriginalParcelPaySide']
                : null,
            rejectReturnParcelPaySide: isset($data['rejectReturnParcelPaySide'])
                ? (string) $data['rejectReturnParcelPaySide']
                : null,
            printReturnParcel: isset($data['printReturnParcel']) ? (bool) $data['printReturnParcel'] : null,
            signatureDocuments: isset($data['signatureDocuments']) ? (bool) $data['signatureDocuments'] : null,
            signaturePenColor: isset($data['signaturePenColor']) ? (string) $data['signaturePenColor'] : null,
            signatureCount: isset($data['signatureCount']) ? (int) $data['signatureCount'] : null,
            signaturePageNumbers: isset($data['signaturePageNumbers'])
                ? (string) $data['signaturePageNumbers']
                : null,
            signatureOtherInstructions: isset($data['signatureOtherInstructions'])
                ? (string) $data['signatureOtherInstructions']
                : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'returnParcelDestination' => $this->returnParcelDestination,
            'returnParcelIsDocument' => $this->returnParcelIsDocument,
            'daysUntilReturn' => $this->daysUntilReturn,
            'returnParcelPaymentSide' => $this->returnParcelPaymentSide,
            'rejectAction' => $this->rejectAction,
            'rejectInstruction' => $this->rejectInstruction,
            'rejectContact' => $this->rejectContact,
            'rejectReturnClient' => $this->rejectReturnClient?->toArray(),
            'rejectReturnAgent' => $this->rejectReturnAgent,
            'rejectReturnOfficeCode' => $this->rejectReturnOfficeCode,
            'rejectReturnAddress' => $this->rejectReturnAddress?->toArray(),
            'rejectOriginalParcelPaySide' => $this->rejectOriginalParcelPaySide,
            'rejectReturnParcelPaySide' => $this->rejectReturnParcelPaySide,
            'printReturnParcel' => $this->printReturnParcel,
            'signatureDocuments' => $this->signatureDocuments,
            'signaturePenColor' => $this->signaturePenColor,
            'signatureCount' => $this->signatureCount,
            'signaturePageNumbers' => $this->signaturePageNumbers,
            'signatureOtherInstructions' => $this->signatureOtherInstructions,
        ];
    }
}
