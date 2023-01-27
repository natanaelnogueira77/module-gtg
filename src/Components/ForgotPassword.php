<?php

namespace Src\Components;

use Exception;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class ForgotPassword 
{
    private $email;
    private $error;

    public function __construct(?string $email) 
    {
        $this->email = $email;
    }

    public function verify(): ?User
    {
        try {
            $errors = [];

            if(!$this->email) {
                $errors['email'] = 'O Email é obrigatório!';
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Este Email é inválido!';
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = 'O Email precisa ter 100 caractéres ou menos!';
            } else {
                $user = User::getByEmail($this->email);
                if(!$user) {
                    $errors['email'] = 'Este Email não foi cadastrado!';
                }
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors);
                return null;
            }

            return $user;
        } catch(Exception $e) {
            $this->error = $e;
            return null;
        }
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}