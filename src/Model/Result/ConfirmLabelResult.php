<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Result;

/**
 * Result of confirmLabel (processLabel) operation.
 */
class ConfirmLabelResult
{
    /**
     * @param array<string, mixed> $rawResponse
     */
    public function __construct(
        private readonly bool $success,
        private readonly array $rawResponse,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRawResponse(): array
    {
        return $this->rawResponse;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            success: !isset($data['error']),
            rawResponse: $data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
        ];
    }
}
