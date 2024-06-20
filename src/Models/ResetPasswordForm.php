<?php

namespace Models;

class ResetPasswordForm extends Model 
{
    public function __construct(
        public ?string $password = null,
        public ?string $passwordConfirm = null
    ) 
    {}

    public function rules(): array 
    {
        return [
            $this->createRule()->required('password')->setMessage(_('A senha é obrigatória!')),
            $this->createRule()->minLength('password', 5)->setMessage(sprintf(_('A senha deve ter %s caractéres ou mais!'), 5)),
            $this->createRule()->required('passwordConfirm')->setMessage(_('A confirmação de senha é obrigatória!')),
            $this->createRule()->match('password', 'passwordConfirm')->setMessage(_('As senhas não correspondem!')),
            $this->createRule()->match('passwordConfirm', 'password')->setMessage(_('As senhas não correspondem!'))
        ];
    }
}