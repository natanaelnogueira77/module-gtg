<?php

namespace GTG\Pay\Exceptions;

use Exception;

class AppException extends Exception 
{
    public function __construct($message, $code = 0, $previous = null) 
    {
        parent::__construct($message, $code, $previous);
    }
}