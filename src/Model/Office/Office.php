<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Office;

use Econt\EcontApi\Model\Location\Address;

/**
 * Econt office (branch) entity.
 */
class Office
{
    /**
     * @param string[] $phones
     * @param string[] $emails
     * @param string[] $shipmentTypes
     */
    public function __construct(
        private readonly int $id,
        private readonly string $code,
        private readonly bool $isMPS,
        private readonly bool $isAPS,
        private readonly string $name,
        private readonly string $nameEn,
        private readonly array $phones,
        private readonly array $emails,
        private readonly Address $address,
        private readonly ?string $info = null,
        private readonly ?string $currency = null,
        private readonly ?string $language = null,
        private readonly ?int $normalBusinessHoursFrom = null,
        private readonly ?int $normalBusinessHoursTo = null,
        private readonly ?int $halfDayBusinessHoursFrom = null,
        private readonly ?int $halfDayBusinessHoursTo = null,
        private readonly array $shipmentTypes = [],
        private readonly ?string $partnerCode = null,
        private readonly ?string $hubCode = null,
        private readonly ?string $hubName = null,
        private readonly ?string $hubNameEn = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isMPS(): bool
    {
        return $this->isMPS;
    }

    public function isAPS(): bool
    {
        return $this->isAPS;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    /**
     * @return string[]
     */
    public function getPhones(): array
    {
        return $this->phones;
    }

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getNormalBusinessHoursFrom(): ?int
    {
        return $this->normalBusinessHoursFrom;
    }

    public function getNormalBusinessHoursTo(): ?int
    {
        return $this->normalBusinessHoursTo;
    }

    public function getHalfDayBusinessHoursFrom(): ?int
    {
        return $this->halfDayBusinessHoursFrom;
    }

    public function getHalfDayBusinessHoursTo(): ?int
    {
        return $this->halfDayBusinessHoursTo;
    }

    /**
     * @return string[]
     */
    public function getShipmentTypes(): array
    {
        return $this->shipmentTypes;
    }

    public function getPartnerCode(): ?string
    {
        return $this->partnerCode;
    }

    public function getHubCode(): ?string
    {
        return $this->hubCode;
    }

    public function getHubName(): ?string
    {
        return $this->hubName;
    }

    public function getHubNameEn(): ?string
    {
        return $this->hubNameEn;
    }

    public function normalBusinessHoursFromAsDateTime(): ?\DateTimeImmutable
    {
        if ($this->normalBusinessHoursFrom === null) {
            return null;
        }

        return (new \DateTimeImmutable())->setTimestamp((int) ($this->normalBusinessHoursFrom / 1000));
    }

    public function normalBusinessHoursToAsDateTime(): ?\DateTimeImmutable
    {
        if ($this->normalBusinessHoursTo === null) {
            return null;
        }

        return (new \DateTimeImmutable())->setTimestamp((int) ($this->normalBusinessHoursTo / 1000));
    }

    public function halfDayBusinessHoursFromAsDateTime(): ?\DateTimeImmutable
    {
        if ($this->halfDayBusinessHoursFrom === null) {
            return null;
        }

        return (new \DateTimeImmutable())->setTimestamp((int) ($this->halfDayBusinessHoursFrom / 1000));
    }

    public function halfDayBusinessHoursToAsDateTime(): ?\DateTimeImmutable
    {
        if ($this->halfDayBusinessHoursTo === null) {
            return null;
        }

        return (new \DateTimeImmutable())->setTimestamp((int) ($this->halfDayBusinessHoursTo / 1000));
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            code: (string) ($data['code'] ?? ''),
            isMPS: (bool) ($data['isMPS'] ?? false),
            isAPS: (bool) ($data['isAPS'] ?? false),
            name: (string) ($data['name'] ?? ''),
            nameEn: (string) ($data['nameEn'] ?? ''),
            phones: array_map('strval', (array) ($data['phones'] ?? [])),
            emails: array_map('strval', (array) ($data['e-mails'] ?? [])),
            address: Address::fromArray($data['address'] ?? []),
            info: isset($data['info']) ? (string) $data['info'] : null,
            currency: isset($data['currency']) ? (string) $data['currency'] : null,
            language: isset($data['language']) ? (string) $data['language'] : null,
            normalBusinessHoursFrom: isset($data['normalBusinessHoursFrom'])
                ? (int) $data['normalBusinessHoursFrom']
                : null,
            normalBusinessHoursTo: isset($data['normalBusinessHoursTo'])
                ? (int) $data['normalBusinessHoursTo']
                : null,
            halfDayBusinessHoursFrom: isset($data['halfDayBusinessHoursFrom'])
                ? (int) $data['halfDayBusinessHoursFrom']
                : null,
            halfDayBusinessHoursTo: isset($data['halfDayBusinessHoursTo'])
                ? (int) $data['halfDayBusinessHoursTo']
                : null,
            shipmentTypes: array_map('strval', (array) ($data['shipmentTypes'] ?? [])),
            partnerCode: isset($data['partnerCode']) ? (string) $data['partnerCode'] : null,
            hubCode: isset($data['hubCode']) ? (string) $data['hubCode'] : null,
            hubName: isset($data['hubName']) ? (string) $data['hubName'] : null,
            hubNameEn: isset($data['hubNameEn']) ? (string) $data['hubNameEn'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'isMPS' => $this->isMPS,
            'isAPS' => $this->isAPS,
            'name' => $this->name,
            'nameEn' => $this->nameEn,
            'phones' => $this->phones,
            'e-mails' => $this->emails,
            'address' => $this->address->toArray(),
            'info' => $this->info,
            'currency' => $this->currency,
            'language' => $this->language,
            'normalBusinessHoursFrom' => $this->normalBusinessHoursFrom,
            'normalBusinessHoursTo' => $this->normalBusinessHoursTo,
            'halfDayBusinessHoursFrom' => $this->halfDayBusinessHoursFrom,
            'halfDayBusinessHoursTo' => $this->halfDayBusinessHoursTo,
            'shipmentTypes' => $this->shipmentTypes,
            'partnerCode' => $this->partnerCode,
            'hubCode' => $this->hubCode,
            'hubName' => $this->hubName,
            'hubNameEn' => $this->hubNameEn,
        ];
    }
}
