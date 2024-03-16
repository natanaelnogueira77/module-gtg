<?php 

namespace Src\Views\Widgets\Sections;

class ResetPassword 
{
    public function __construct(
        private string $formId,
        private string $formAction,
        private string $formMethod,
        private ?string $redirectURL = null,
        private bool $hasToken = false
    ) 
    {}

    public function getFormId(): string 
    {
        return $this->formId;
    }

    public function getFormAction(): string 
    {
        return $this->formAction;
    }

    public function getFormMethod(): string 
    {
        return $this->formMethod;
    }

    public function getResetPasswordURL(): string 
    {
        return $this->resetPasswordURL;
    }
    
    public function getRedirectURL(): string 
    {
        return $this->redirectURL ?? '';
    }

    public function hasRedirectURL(): bool 
    {
        return $this->redirectURL ? true : false;
    }

    public function hasToken(): bool 
    {
        return $this->hasToken;
    }
}