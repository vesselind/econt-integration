<?php

declare(strict_types=1);

namespace Econt\EcontApi\Exception;

/**
 * Thrown on transport-level failures (timeout, DNS errors, HTTP 5xx, etc.).
 */
class EcontNetworkException extends EcontException
{
    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
