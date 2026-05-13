<?php

declare(strict_types=1);

namespace Econt\EcontApi\Exception;

/**
 * Thrown when the Econt API returns a business-level error response.
 */
class EcontApiException extends EcontException
{
    public function __construct(
        string $message,
        private readonly string $apiErrorCode = '',
        private readonly string $apiErrorMessage = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getApiErrorCode(): string
    {
        return $this->apiErrorCode;
    }

    public function getApiErrorMessage(): string
    {
        return $this->apiErrorMessage;
    }
}
