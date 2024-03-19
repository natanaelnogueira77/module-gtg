<?php 

namespace Src\Views\Pages;

class ResetPasswordPage 
{
    public function __construct(
        private string $formAction,
        private ?string $redirectURL = null,
        private bool $hasToken = false
    ) 
    {}

    public function getFormAction(): string 
    {
        return $this->formAction;
    }

    public function getRedirectURL(): ?string 
    {
        return $this->redirectURL;
    }

    public function hasToken(): bool 
    {
        return $this->hasToken;
    }
}