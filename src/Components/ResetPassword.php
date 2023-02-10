<?php

namespace Src\Components;

use Exception;
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
            $lang = getLang()->setFilepath('components/reset-password')->getContent()->setBase('verify');
            $errors = [];
            
            if(!$this->password) {
                $errors['password'] = $lang->get('password.required');
            }
    
            if(!$this->confirm_password) {
                $errors['confirm_password'] = $lang->get('confirm_password.required');
            }
    
            if($this->password 
                && $this->confirm_password 
                && $this->password !== $this->confirm_password) {
                $errors['password'] = $lang->get('password_match');
                $errors['confirme_password'] = $lang->get('password_match');
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, $lang->get('error_message'));
                return null;
            }

            $user = User::getByToken($token);
            if($user) {
                $user->password = $this->password;
                $user->save();

                return $user;
            }

            throw new AppException($lang->get('invalid_key'));
        } catch(Exception $e) {
            $this->error = $e;
        }
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}