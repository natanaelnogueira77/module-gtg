<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class Login 
{
    private $email;
    private $password;
    private $error;

    public function __construct(?string $email, ?string $password) 
    {
        $this->email = $email;
        $this->password = $password;
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
            }

            if(!$this->password) {
                $errors['password'] = _('A senha é obrigatória!');
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            }

            $user = User::getByEmail($this->email);
            if($user && $user->verifyPassword($this->password)) {
                return $user;
            }
            
            throw new AppException(_('O email ou a senha estão incorretos!'));
        } catch(AppException $e) {
            $this->error = $e;
            return null;
        }
    }

    public function error(): ?AppException 
    {
        return $this->error;
    }
}