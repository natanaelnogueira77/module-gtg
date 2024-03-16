<?php 

namespace Src\Views\Widgets\Sections;

class LoginForm 
{
    public function __construct(
        private string $formId,
        private string $formAction,
        private string $formMethod,
        private string $resetPasswordURL,
        private string $backgroundImageURL,
        private ?string $redirectURL = null
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

    public function getBackgroundImageURL(): string 
    {
        return $this->backgroundImageURL;
    }
    
    public function getRedirectURL(): string 
    {
        return $this->redirectURL ?? '';
    }

    public function hasRedirectURL(): bool 
    {
        return $this->redirectURL ? true : false;
    }
}