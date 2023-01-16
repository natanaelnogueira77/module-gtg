<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class ResetPassword 
{
    private $password;
    private $confirm_password;

    public function __construct(?string $password, ?string $confirm_password) 
    {
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
    
    public function validate(): void
    {
        $errors = [];
        
        if(!$this->password) {
            $errors['password'] = 'A Senha é obrigatória!';
        }

        if(!$this->confirm_password) {
            $errors['confirm_password'] = 'A Confirmação de Senha é obrigatória!';
        }

        if($this->password 
            && $this->confirm_password 
            && $this->password !== $this->confirm_password) {
            $errors['password'] = 'As senhas não correspondem!';
            $errors['confirme_password'] = 'As senhas não correspondem!';
        }
        
        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function check(string $token): ?User
    {
        $this->validate();
        
        $user = User::getByToken($token);
        if($user) {
            $user->password = $this->password;
            $user->save();

            return $user;
        }

        throw new AppException('Chave de verificação é inválida!');
    }
}