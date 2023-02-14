<?php

namespace Src\Components;

use Exception;
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
                $errors['email'] = _('O Email é obrigatório!');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = _('O Email é inválido!');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = _('O Email precisa ter 100 caractéres ou menos!');
            }

            if(!$this->password) {
                $errors['password'] = _('Senha é obrigatória!');
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, _('Erros de Validação! Verifique os campos.'));
            }

            $user = User::getByEmail($this->email);
            if($user && $user->verifyPassword($this->password)) {
                return $user;
            }
            
            throw new AppException(_('Usuário ou Senha inválidos!'));
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