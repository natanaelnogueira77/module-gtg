<?php

namespace Src\Exceptions;

use Throwable;

class RedirectException extends ApplicationException 
{
    private string $redirectURL = '';

    public function __construct(
        string $redirectURL, 
        string $message, 
        int $code = 0, 
        ?Throwable $previous = null
    ) 
    {
        parent::__construct($message, $code, $previous);
        $this->redirectURL = $redirectURL;
    }

    public function getRedirectURL(): string 
    {
        return $this->redirectURL;
    }
}