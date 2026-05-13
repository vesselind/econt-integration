<?php

declare(strict_types=1);

namespace Econt\EcontApi\Exception;

class EcontApiException extends EcontException
{
    private ?string $errorCode;
    private ?string $errorMessage;

    public function __construct(
        ?string $errorCode = null,
        ?string $errorMessage = null,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;

        $fullMessage = $message;
        if ($errorCode !== null || $errorMessage !== null) {
            $fullMessage = trim(($errorCode ? "[{$errorCode}] " : '') . ($errorMessage ?: '') . ' ' . $message);
        }

        parent::__construct($fullMessage ?: 'Econt API error occurred', $code, $previous);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}