<?php 

namespace Views\Components\Emails;

use GTG\MVC\Application;
use Models\AR\User;

final class ResetPasswordEmail extends Email
{
    public function __construct(
        private readonly User $user, 
        public readonly string $siteUrl, 
        private readonly ?string $redirectUrl = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/emails/reset-password', ['view' => $this]);
    }

    public function getLink(): string 
    {
        return Application::getInstance()->router->route('resetPassword.verify', array_merge([
            'token' => $this->user->token
        ], $this->redirectUrl ? ['redirect' => $this->redirectUrl] : []));
    }
}