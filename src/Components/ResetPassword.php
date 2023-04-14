<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class ResetPassword 
{
    private $password;
    private $confirm_password;
    private $error;

    public function __construct(?string $password, ?string $confirm_password) 
    {
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
    
    public function verify(string $token): ?User
    {
        try {
            $errors = [];
            
            if(!$this->password) {
                $errors['password'] = _('A senha é obrigatória!');
            }
    
            if(!$this->confirm_password) {
                $errors['confirm_password'] = _('A confirmação de senha é obrigatória!');
            }
    
            if($this->password 
                && $this->confirm_password 
                && $this->password !== $this->confirm_password) {
                $errors['password'] = _('As senhas não correspondem!');
                $errors['confirm_password'] = _('As senhas não correspondem!');
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            }

            $user = User::getByToken($token);
            if($user) {
                $user->password = $this->password;
                $user->save();

                return $user;
            }

            throw new AppException(_('A chave de verificação é inválida!'));
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