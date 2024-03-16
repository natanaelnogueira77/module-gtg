<?php

namespace Src\Models;

use Src\Models\AR\User;
use Src\Models\Model;

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
            $this->createRule()->required('password')->setMessage(_('The password is required!')),
            $this->createRule()->minLength('password', 5)->setMessage(sprintf(_('The password must have %s characters or more!'), 5)),
            $this->createRule()->required('passwordConfirm')->setMessage(_('The password confirmation is required!')),
            $this->createRule()->match('password', 'passwordConfirm')->setMessage(_('The passwords does not match!')),
            $this->createRule()->match('passwordConfirm', 'password')->setMessage(_('The passwords does not match!'))
        ];
    }
}