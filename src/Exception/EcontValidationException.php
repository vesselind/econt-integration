<?php

declare(strict_types=1);

namespace Econt\EcontApi\Exception;

class EcontValidationException extends EcontException
{
    private array $validationErrors;

    public function __construct(
        array $validationErrors = [],
        string $message = 'Validation failed',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->validationErrors = $validationErrors;
        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}