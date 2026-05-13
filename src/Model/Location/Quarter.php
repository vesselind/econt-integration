<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * Quarter (neighbourhood) entity returned by the Econt API.
 */
class Quarter
{
    public function __construct(
        private readonly int $id,
        private readonly int $cityID,
        private readonly string $name,
        private readonly string $nameEn,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityID(): int
    {
        return $this->cityID;
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
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            cityID: (int) ($data['cityID'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            nameEn: (string) ($data['nameEn'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'cityID' => $this->cityID,
            'name' => $this->name,
            'nameEn' => $this->nameEn,
        ];
    }
}
