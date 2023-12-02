<?php

namespace GTG\MVC\Example\Src\Models;

use GTG\MVC\Model;
use GTG\MVC\Example\Src\Models\User;

class ResetPasswordForm extends Model 
{
    public ?string $password = null;
    public ?string $password_confirm = null;

    public function rules(): array 
    {
        return [
            'password' => [
                [self::RULE_REQUIRED, 'message' => 'The password is required!'], 
                [self::RULE_MIN, 'min' => 5, 'message' => sprintf('The password must have %s characters or more!', 5)]
            ],
            'password_confirm' => [
                [self::RULE_REQUIRED, 'message' => 'The password confirmation is required!'], 
                [self::RULE_MATCH, 'match' => 'password', 'message' => 'The password does not match!']
            ]
        ];
    }
}