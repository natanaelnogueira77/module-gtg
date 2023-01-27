<?php

namespace Src\Components;

use Exception;
use ReCaptcha\ReCaptcha;
use Src\Components\Email;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;

class Contact 
{
    private $name = '';
    private $email = '';
    private $subject = '';
    private $message = '';
    private $recaptchaResponse;
    private $error;

    public function __construct(string $subject, string $message, string $name, string $email, $recaptchaResponse) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;

        $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
        $resp = $recaptcha->setExpectedHostname(RECAPTCHA['host'])->verify($recaptchaResponse);

        $this->recaptchaResponse = $resp;
    }
    
    public function send(): bool 
    {
        try {
            $errors = [];
    
            if(!$this->name) {
                $errors['name'] = 'O Nome é obrigatório!';
            } elseif(strlen($this->name) > 100) {
                $errors['name'] = 'O Nome precisa ter 100 caractéres ou menos!';
            }
    
            if(!$this->email) {
                $errors['email'] = 'O Email é obrigatório!';
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Este Email é inválido!';
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = 'O Email precisa ter 100 caractéres ou menos!';
            }
    
            if(!$this->subject) {
                $errors['subject'] = 'O Assunto é obrigatório!';
            } elseif(strlen($this->subject) > 100) {
                $errors['subject'] = 'O Assunto precisa ter 100 caractéres ou menos!';
            }
    
            if(!$this->message) {
                $errors['message'] = 'A Mensagem é obrigatória!';
            } elseif(strlen($this->message) > 1000) {
                $errors['message'] = 'A Mensagem precisa ter 1000 caractéres ou menos!';
            }
    
            if(!$this->recaptchaResponse->isSuccess()) {
                $errors['recaptcha'] = 'Você precisa completar o teste do ReCaptcha!';
            }
    
            if(count($errors) > 0) {
                throw new ValidationException($errors);
            }

            $email = new Email();
            if(!$email->add($this->subject, $this->message, $this->name, ERROR_MAIL)->send($this->name, $this->email)) {
                throw $email->error();
            }
        } catch(Exception $e) {
            $this->error = $e;
            return false;
        }

        return true;
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}