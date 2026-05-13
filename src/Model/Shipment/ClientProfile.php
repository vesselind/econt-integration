<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

use Econt\EcontApi\Model\Location\Address;

/**
 * Client profile (sender or receiver) for a shipment label.
 */
class ClientProfile
{
    /**
     * @param string[]      $phones
     * @param string[]|null $skypeAccounts
     */
    public function __construct(
        private readonly string $name,
        private readonly array $phones,
        private readonly ?int $id = null,
        private readonly ?string $nameEn = null,
        private readonly ?string $email = null,
        private readonly ?array $skypeAccounts = null,
        private readonly ?string $clientNumber = null,
        private readonly ?string $clientNumberEn = null,
        private readonly ?int $juridicalEntity = null,
        private readonly ?string $personalIDType = null,
        private readonly ?string $personalIDNumber = null,
        private readonly ?string $companyType = null,
        private readonly ?string $ein = null,
        private readonly ?string $ddsEinPrefix = null,
        private readonly ?string $ddsEin = null,
        private readonly ?Address $registrationAddress = null,
        private readonly ?string $molName = null,
        private readonly ?string $molEGN = null,
        private readonly ?string $molIDNum = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): ?string
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string[]|null
     */
    public function getSkypeAccounts(): ?array
    {
        return $this->skypeAccounts;
    }

    public function getClientNumber(): ?string
    {
        return $this->clientNumber;
    }

    public function getClientNumberEn(): ?string
    {
        return $this->clientNumberEn;
    }

    public function getJuridicalEntity(): ?int
    {
        return $this->juridicalEntity;
    }

    public function getPersonalIDType(): ?string
    {
        return $this->personalIDType;
    }

    public function getPersonalIDNumber(): ?string
    {
        return $this->personalIDNumber;
    }

    public function getCompanyType(): ?string
    {
        return $this->companyType;
    }

    public function getEin(): ?string
    {
        return $this->ein;
    }

    public function getDdsEinPrefix(): ?string
    {
        return $this->ddsEinPrefix;
    }

    public function getDdsEin(): ?string
    {
        return $this->ddsEin;
    }

    public function getRegistrationAddress(): ?Address
    {
        return $this->registrationAddress;
    }

    public function getMolName(): ?string
    {
        return $this->molName;
    }

    public function getMolEGN(): ?string
    {
        return $this->molEGN;
    }

    public function getMolIDNum(): ?string
    {
        return $this->molIDNum;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) ($data['name'] ?? ''),
            phones: array_map('strval', (array) ($data['phones'] ?? [])),
            id: isset($data['id']) ? (int) $data['id'] : null,
            nameEn: isset($data['nameEn']) ? (string) $data['nameEn'] : null,
            email: isset($data['e-mail']) ? (string) $data['e-mail'] : null,
            skypeAccounts: isset($data['skypeAccounts'])
                ? array_map('strval', (array) $data['skypeAccounts'])
                : null,
            clientNumber: isset($data['clientNumber']) ? (string) $data['clientNumber'] : null,
            clientNumberEn: isset($data['clientNumberEn']) ? (string) $data['clientNumberEn'] : null,
            juridicalEntity: isset($data['juridicalEntity']) ? (int) $data['juridicalEntity'] : null,
            personalIDType: isset($data['personalIDType']) ? (string) $data['personalIDType'] : null,
            personalIDNumber: isset($data['personalIDNumber']) ? (string) $data['personalIDNumber'] : null,
            companyType: isset($data['companyType']) ? (string) $data['companyType'] : null,
            ein: isset($data['ein']) ? (string) $data['ein'] : null,
            ddsEinPrefix: isset($data['ddsEinPrefix']) ? (string) $data['ddsEinPrefix'] : null,
            ddsEin: isset($data['ddsEin']) ? (string) $data['ddsEin'] : null,
            registrationAddress: isset($data['registrationAddress']) && is_array($data['registrationAddress'])
                ? Address::fromArray($data['registrationAddress'])
                : null,
            molName: isset($data['molName']) ? (string) $data['molName'] : null,
            molEGN: isset($data['molEGN']) ? (string) $data['molEGN'] : null,
            molIDNum: isset($data['molIDNum']) ? (string) $data['molIDNum'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'nameEn' => $this->nameEn,
            'phones' => $this->phones,
            'e-mail' => $this->email,
            'skypeAccounts' => $this->skypeAccounts,
            'clientNumber' => $this->clientNumber,
            'clientNumberEn' => $this->clientNumberEn,
            'juridicalEntity' => $this->juridicalEntity,
            'personalIDType' => $this->personalIDType,
            'personalIDNumber' => $this->personalIDNumber,
            'companyType' => $this->companyType,
            'ein' => $this->ein,
            'ddsEinPrefix' => $this->ddsEinPrefix,
            'ddsEin' => $this->ddsEin,
            'registrationAddress' => $this->registrationAddress?->toArray(),
            'molName' => $this->molName,
            'molEGN' => $this->molEGN,
            'molIDNum' => $this->molIDNum,
        ];

        return $result;
    }
}
