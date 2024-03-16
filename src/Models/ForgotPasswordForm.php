<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\AR\User;
use Src\Models\AR\UserMeta;
use Src\Models\Model;

class ForgotPasswordForm extends Model 
{
    public function __construct(
        public ?string $email = null
    ) 
    {}

    public function rules(): array 
    {
        return [
            'email' => [
                $this->createRule()->required('email')->setMessage(_('The email is required!')),
                $this->createRule()->email('email')->setMessage(_('The email is invalid!')),
                $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('The email must have %s characters or less!'), 100))
            ]
        ];
    }

    public function getUser(): ?User 
    {
        $this->validate();

        if(!$user = User::getByEmail($this->email)) {
            throw new ValidationException([
                'email' => _('This email was not found!'),
                _('This email was not found!')
            ]);
        } elseif($lastRequest = $user->getLastResetPasswordRequest()) {
            if(strtotime($lastRequest) >= strtotime('-1 hour')) {
                throw new ValidationException([
                    'email' => _('A request was already sent to this email! Wait 1 hour to send another.'),
                    _('A request was already sent to this email! Wait 1 hour to send another.')
                ]);
            }
        }

        return $user;
    }
}