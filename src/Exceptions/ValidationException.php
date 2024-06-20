<?php

namespace Exceptions;

use Throwable;

class ValidationException extends ApplicationException 
{
    private $errors = [];

    public function __construct(
        array $errors, 
        string $message, 
        int $code = 422, 
        ?Throwable $previous = null
    ) 
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array 
    {
        return $this->errors;
    }

    public function get(string $att): string 
    {
        return $this->errors[$att];
    }
}