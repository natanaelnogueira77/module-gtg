<?php

namespace Src\Models;

use Src\Exceptions\ApplicationException;
use Src\Models\AR\User;
use Src\Models\Model;

class LoginForm extends Model 
{
    public function __construct(
        public ?string $email = null,
        public ?string $password = null
    ) 
    {}

    public function rules(): array 
    {
        return [
            $this->createRule()->required('email')->setMessage(_('The email is required!')),
            $this->createRule()->email('email')->setMessage(_('The email is invalid!')),
            $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('The email must have %s characters or less!'), 100)),
            $this->createRule()->required('password')->setMessage(_('The password is required!'))
        ];
    }

    public function login(): ?User 
    {
        $this->validate();

        if(!($user = User::getByEmail($this->email)) || !$user->verifyPassword($this->password)) {
            throw new ApplicationException(_('The email/password are incorrect!'));
        }

        return $user;
    }
}