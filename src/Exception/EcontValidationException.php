<?php

declare(strict_types=1);

namespace Econt\EcontApi\Exception;

/**
 * Thrown when client-side validation of a request payload fails.
 */
class EcontValidationException extends EcontException
{
    /**
     * @param string[] $violations
     */
    public function __construct(
        string $message,
        private readonly array $violations = [],
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
