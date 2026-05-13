<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Office;

use Econt\EcontApi\Model\AbstractModel;

class OfficeWorkHours extends AbstractModel
{
    private ?string $dayOfWeek;
    private ?string $fromHour;
    private ?string $toHour;
    private ?bool $isNonWorking;

    public function __construct(
        ?string $dayOfWeek = null,
        ?string $fromHour = null,
        ?string $toHour = null,
        ?bool $isNonWorking = null
    ) {
        $this->dayOfWeek = $dayOfWeek;
        $this->fromHour = $fromHour;
        $this->toHour = $toHour;
        $this->isNonWorking = $isNonWorking;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    public function getFromHour(): ?string
    {
        return $this->fromHour;
    }

    public function setFromHour(?string $fromHour): self
    {
        $this->fromHour = $fromHour;
        return $this;
    }

    public function getToHour(): ?string
    {
        return $this->toHour;
    }

    public function setToHour(?string $toHour): self
    {
        $this->toHour = $toHour;
        return $this;
    }

    public function isNonWorking(): ?bool
    {
        return $this->isNonWorking;
    }

    public function setIsNonWorking(?bool $isNonWorking): self
    {
        $this->isNonWorking = $isNonWorking;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['dayOfWeek'] ?? null,
            $data['fromHour'] ?? null,
            $data['toHour'] ?? null,
            $data['isNonWorking'] ?? null
        );
    }
}