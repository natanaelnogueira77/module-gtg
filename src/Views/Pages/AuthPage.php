<?php 

namespace Src\Views\Pages;

class AuthPage 
{
    public function __construct(
        private string $loginImageURL,
        private ?string $redirectURL = null
    ) 
    {}

    public function getLoginImageURL(): string 
    {
        return $this->loginImageURL;
    }

    public function getRedirectURL(): ?string 
    {
        return $this->redirectURL;
    }
}