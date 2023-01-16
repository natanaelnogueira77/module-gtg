<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class Login 
{
    private $email;
    private $password;

    public function __construct(?string $email, ?string $password) 
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function validate(): void 
    {
        $errors = [];

        if(!$this->email) {
            $errors['email'] = 'Email é obrigatório!';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido!';
        }

        if(!$this->password) {
            $errors['password'] = 'Senha é obrigatório!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkLogin() 
    {
        $this->validate();

        $user = User::getByEmail($this->email);
        if($user && $user->verifyPassword($this->password)) {
            return $user;
        }
        
        throw new AppException('Usuário ou Senha inválidos!');
    }
}