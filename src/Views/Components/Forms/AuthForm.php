<?php 

namespace Views\Components\Forms;

use Views\Widget;

class AuthForm extends Widget
{
    public function __construct(
        public readonly string $id,
        public readonly string $action,
        public readonly string $method,
        public readonly string $backgroundImage,
        public readonly string $logoIconUrl,
        public readonly string $resetPasswordUrl,
        public readonly ?string $redirectUrl = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/forms/auth', ['view' => $this]);
    }
}