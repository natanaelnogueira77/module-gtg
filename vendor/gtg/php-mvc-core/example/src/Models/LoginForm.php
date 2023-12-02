<?php

namespace GTG\MVC\Example\Src\Models;

use GTG\MVC\Model;
use GTG\MVC\Example\Src\Models\User;

class LoginForm extends Model 
{
    public ?string $email = null;
    public ?string $password = null;

    public function rules(): array 
    {
        return [
            'email' => [
                [self::RULE_REQUIRED, 'message' => 'The email is required!'], 
                [self::RULE_EMAIL, 'message' => 'The name is invalid!']
            ],
            'password' => [
                [self::RULE_REQUIRED, 'message' => 'The password is required']
            ]
        ];
    }

    public function login(): ?User 
    {
        if(!$this->validate()) {
            return null;
        }

        if(!$user = User::getByEmail($this->email)) {
            $this->addError('email', 'The email was not found!');
            return null;
        }
        
        if(!$user->verifyPassword($this->password)) {
            $this->addError('password', 'The password is incorrect');
            return null;
        }

        return $user;
    }
}