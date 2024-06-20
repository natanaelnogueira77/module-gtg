<?php

namespace Exceptions;

use Exception, Throwable;

class ApplicationException extends Exception 
{
    public function __construct(
        string $message = '', 
        int $code = 500, 
        ?Throwable $previous = null
    ) 
    {
        parent::__construct($message, $code, $previous);
    }
}