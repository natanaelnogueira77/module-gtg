<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class ForgotPassword 
{
    private $email;

    public function __construct(?string $email) 
    {
        $this->email = $email;
    }

    public function validate(): void
    {
        $errors = [];

        if(!$this->email) {
            $errors['email'] = 'O Email é obrigatório!';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Este Email é inválido!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function check() 
    {
        $this->validate();
        
        $user = User::getByEmail($this->email);
        if($user) {
            if($this->email == $user->email) {
                return $user;
            }
        }
        
        throw new AppException('Email é Inválido!');
    }
}