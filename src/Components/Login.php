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
            $lang = getLang()->setFilepath('components/login')->getContent()->setBase('verify');
            $errors = [];

            if(!$this->email) {
                $errors['email'] = $lang->get('email.required');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = $lang->get('email.invalid');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = $lang->get('email.max');
            }

            if(!$this->password) {
                $errors['password'] = $lang->get('password.required');
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, $lang->get('error_message'));
            }

            $user = User::getByEmail($this->email);
            if($user && $user->verifyPassword($this->password)) {
                return $user;
            }
            
            throw new AppException($lang->get('invalid_user'));
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