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
            $lang = getLang()->setFilepath('components/forgot-password')->getContent()->setBase('verify');
            $errors = [];

            if(!$this->email) {
                $errors['email'] = $lang->get('email.required');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = $lang->get('email.invalid');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = $lang->get('email.max');
            } else {
                $user = User::getByEmail($this->email);
                if(!$user) {
                    $errors['email'] = $lang->get('email.exists');
                }
            }

            if(count($errors) > 0) {
                throw new ValidationException($errors, $lang->get('error_message'));
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