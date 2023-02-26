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
                $errors['email'] = _('O email é obrigatório!');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = _('O email é inválido!');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = _('O email precisa ter 100 caractéres ou menos!');
            } else {
                $user = User::getByEmail($this->email);
                if(!$user) {
                    $errors['email'] = _('O email não foi encontrado!');
                }
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
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