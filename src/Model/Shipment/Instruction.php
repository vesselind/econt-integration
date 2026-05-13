<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Shipment;

/**
 * Delivery or pick-up instruction attached to a shipment label.
 */
class Instruction
{
    /**
     * @param array<mixed>|null $attachments
     */
    public function __construct(
        private readonly string $type,
        private readonly ?string $title = null,
        private readonly ?string $description = null,
        private readonly ?array $attachments = null,
        private readonly ?string $voiceDescription = null,
        private readonly ?string $name = null,
        private readonly ?bool $applyToAllParcels = null,
        private readonly ?bool $applyToReceivers = null,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array<mixed>|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    public function getVoiceDescription(): ?string
    {
        return $this->voiceDescription;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getApplyToAllParcels(): ?bool
    {
        return $this->applyToAllParcels;
    }

    public function getApplyToReceivers(): ?bool
    {
        return $this->applyToReceivers;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: (string) ($data['type'] ?? ''),
            title: isset($data['title']) ? (string) $data['title'] : null,
            description: isset($data['description']) ? (string) $data['description'] : null,
            attachments: isset($data['attachments']) && is_array($data['attachments'])
                ? $data['attachments']
                : null,
            voiceDescription: isset($data['voiceDescription']) ? (string) $data['voiceDescription'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            applyToAllParcels: isset($data['applyToAllParcels']) ? (bool) $data['applyToAllParcels'] : null,
            applyToReceivers: isset($data['applyToReceivers']) ? (bool) $data['applyToReceivers'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'attachments' => $this->attachments,
            'voiceDescription' => $this->voiceDescription,
            'name' => $this->name,
            'applyToAllParcels' => $this->applyToAllParcels,
            'applyToReceivers' => $this->applyToReceivers,
        ];
    }
}
