<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Location;

/**
 * Geographic coordinate with accuracy confidence level.
 */
class GeoLocation
{
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly ?int $confidence = null,
    ) {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getConfidence(): ?int
    {
        return $this->confidence;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            latitude: (float) ($data['latitude'] ?? 0.0),
            longitude: (float) ($data['longitude'] ?? 0.0),
            confidence: isset($data['confidence']) ? (int) $data['confidence'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'confidence' => $this->confidence,
        ];
    }
}
