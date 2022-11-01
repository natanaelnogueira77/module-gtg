<?php

namespace Src\Core\System;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\User;
use Src\Core\System\Control;

class ChangePassword extends Control 
{
    public function validateEmail(): void
    {
        $errors = [];

        if(!$this->redefine_email) {
            $errors['redefine_email'] = 'Por favor, informe seu e-mail!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function validatePassword(): void
    {
        $errors = [];
        
        if(!$this->redefine_senha) {
            $errors['redefine_senha'] = 'Senha é um campo obrigatório.';
        }

        if(!$this->confirm_redefine_senha) {
            $errors['confirm_redefine_senha'] = 'Confirmação de Senha é um campo obrigatório.';
        }

        if($this->redefine_senha 
            && $this->confirm_redefine_senha 
            && $this->redefine_senha !== $this->confirm_redefine_senha) {
            $errors['redefine_senha'] = 'As senhas não correspondem!';
            $errors['confirme_redefine_senha'] = 'As senhas não correspondem!';
        }
        
        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function checkEmail() 
    {
        $this->validateEmail();
        $this->updateToken();

        $user = User::getByEmail($this->redefine_email);
        if($user) {
            if($this->redefine_email == $user->email) {
                return $user;
            }
        }
        
        throw new AppException('E-mail Inválido!');
    }
    
    public function checkPassword($token): void
    {
        $this->validatePassword();
        $user = User::getOne(['token' => $token]);
        
        if($user) {
            $user->password = $this->redefine_senha;
            $user->save();
        }
    }
    
    public function updateToken(): void
    {
        $today = date('Y-m-d');
        $code = "{$this->redefine_email}{$today}";
        $token = md5($code);
        $user = User::getByEmail($this->redefine_email);
        
        if($user) {
            $user->token = $token;
            $user->save();
        }
    }
}