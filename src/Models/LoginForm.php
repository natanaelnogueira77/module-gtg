<?php

namespace Src\Models;

use Src\Exceptions\ApplicationException;
use Src\Models\AR\User;

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
            $this->createRule()->required('email')->setMessage(_('O email é obrigatório!')),
            $this->createRule()->email('email')->setMessage(_('O email é invalido!')),
            $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('O email deve ter %s caractéres ou menos!'), 100)),
            $this->createRule()->required('password')->setMessage(_('A senha é obrigatória!'))
        ];
    }

    public function login(): ?User 
    {
        $this->validate();

        if(!($user = User::getByEmail($this->email)) || !$user->verifyPassword($this->password)) {
            throw new ApplicationException(_('O email/senha estão incorretos!'), 422);
        }

        return $user;
    }
}