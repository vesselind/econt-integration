<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * Country entity returned by the Econt API.
 */
class Country
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $code2,
        private readonly string $code3,
        private readonly ?string $name,
        private readonly ?string $nameEn,
        private readonly ?bool $isEU = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode2(): string
    {
        return $this->code2;
    }

    public function getCode3(): string
    {
        return $this->code3;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function isEU(): ?bool
    {
        return $this->isEU;
    }

    public static function bulgaria(): self
    {
        return self::create(code2: 'BG', code3: 'BGR', name: 'България', nameEn: 'Bulgaria');
    }

    public static function romania(): self
    {
        return self::create(code2: 'RO', code3: 'ROU', name: 'Румъния', nameEn: 'Romania');
    }

    public static function greece(): self
    {
        return self::create(code2: 'GR', code3: 'GRC', name: 'Гърция', nameEn: 'Greece');
    }

    /**
     * Creates a Country without a known API id — useful for building request payloads.
     */
    public static function create(
        string $code2,
        string $code3,
        ?string $name = null,
        ?string $nameEn = null,
        ?bool $isEU = null,
    ): self {
        return new self(id: null, code2: $code2, code3: $code3, name: $name, nameEn: $nameEn, isEU: $isEU);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            code2: (string) ($data['code2'] ?? ''),
            code3: (string) ($data['code3'] ?? ''),
            name: isset($data['name']) ? (string) $data['name'] : null,
            nameEn: isset($data['nameEn']) ? (string) $data['nameEn'] : null,
            isEU: isset($data['isEU']) ? (bool) $data['isEU'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code2' => $this->code2,
            'code3' => $this->code3,
            'name' => $this->name,
            'nameEn' => $this->nameEn,
            'isEU' => $this->isEU,
        ];
    }
}
