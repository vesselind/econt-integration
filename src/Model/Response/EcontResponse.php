<?php

declare(strict_types=1);

namespace Econt\EcontApi\Model\Response;

use Econt\EcontApi\Model\AbstractModel;

class EcontResponse extends AbstractModel
{
    private bool $isSuccess;
    private ?string $errorMessage;
    private ?string $errorCode;
    private mixed $data;

    public function __construct(
        bool $isSuccess = false,
        ?string $errorMessage = null,
        ?string $errorCode = null,
        mixed $data = null
    ) {
        $this->isSuccess = $isSuccess;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->data = $data;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public static function success(mixed $data = null): self
    {
        return new self(true, null, null, $data);
    }

    public static function error(string $errorMessage, ?string $errorCode = null): self
    {
        return new self(false, $errorMessage, $errorCode, null);
    }

    public static function fromArray(array $data): static
    {
        $isSuccess = $data['is_success'] ?? $data['isSuccess'] ?? false;
        $errorMessage = $data['error_message'] ?? $data['errorMessage'] ?? null;
        $errorCode = $data['error_code'] ?? $data['errorCode'] ?? null;
        $responseData = $data['data'] ?? null;

        return new self($isSuccess, $errorMessage, $errorCode, $responseData);
    }
}