<?php

namespace GTG\MVC\Example\Src\Models;

use GTG\MVC\Model;
use GTG\MVC\Example\Src\Models\User;

class ForgotPasswordForm extends Model 
{
    public ?string $email = null;

    public function rules(): array 
    {
        return [
            'email' => [
                [self::RULE_REQUIRED, 'message' => 'The email is required!'], 
                [self::RULE_EMAIL, 'message' => 'The name is invalid!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The email must have %s characters or less!', 100)]
            ]
        ];
    }

    public function user(): ?User 
    {
        if(!$this->validate()) {
            return null;
        }

        if(!$user = User::getByEmail($this->email)) {
            $this->addError('email', _('The email was not found!'));
            return null;
        }

        return $user;
    }
}