<?php

namespace Src\Components;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;

class Contact 
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    public function __construct(string $subject, string $message, string $name, string $email) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function validate(): void
    {
        $errors = [];

        if(!$this->name) {
            $errors['name'] = 'O Nome é obrigatório!';
        }
    
        if(!$this->email) {
            $errors['email'] = 'O Email é obrigatório!';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Este Email é inválido!';
        }
        
        if(!$this->subject) {
            $errors['subject'] = 'O Assunto é obrigatório!';
        }
    
        if(!$this->message) {
            $errors['message'] = 'A Mensagem é obrigatória!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function check(): void 
    {
        $this->validate();
    }
}