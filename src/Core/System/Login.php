<?php

namespace Src\Core\System;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\User;
use Src\Core\System\Control;

class Login extends Control 
{
    public function validate(): void 
    {
        $errors = [];

        if(!$this->login_email) {
            $errors['login_email'] = 'Este é um campo obrigatório!';
        }

        if(!$this->login_senha) {
            $errors['login_senha'] = 'Por favor, informe sua senha!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkLogin() 
    {
        $this->validate();
        $user = User::getOne(['email' => $this->login_email]);
        if($user) {
            if(password_verify($this->login_senha, $user->password) 
                || md5($this->login_senha) == $user->password) {
                return $user;
            }
        }

        throw new AppException('Login e Senha inválidos.');
    }
}