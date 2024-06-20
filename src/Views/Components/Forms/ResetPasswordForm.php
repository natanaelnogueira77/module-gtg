<?php 

namespace Views\Components\Forms;

use Views\Widget;

class ResetPasswordForm extends Widget
{
    public function __construct(
        public readonly string $id,
        public readonly string $action,
        public readonly string $method,
        public readonly string $backgroundImage,
        public readonly string $logoIconUrl,
        public readonly ?string $redirectUrl = null, 
        public readonly bool $hasToken = false
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/forms/reset-password', ['view' => $this]);
    }
}