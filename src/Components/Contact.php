<?php

namespace Src\Components;

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

    public function __construct(
        string $subject, 
        string $message, 
        string $name, 
        string $email, 
        $recaptchaResponse, 
        $action,
        $serverName,
        $remoteAddr
    ) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;

        $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
        $resp = $recaptcha->setExpectedHostname($serverName)
            ->setExpectedAction($action)
            ->setScoreThreshold(0.5)
            ->verify($recaptchaResponse, $remoteAddr);

        $this->recaptchaResponse = $resp;
    }
    
    public function send(): bool 
    {
        try {
            $errors = [];

            if(!$this->name) {
                $errors['name'] = _('O nome é obrigatório!');
            } elseif(strlen($this->name) > 100) {
                $errors['name'] = _('O nome precisa ter 100 caractéres ou menos!');
            }
    
            if(!$this->email) {
                $errors['email'] = _('O email é obrigatório!');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = _('O email é inválido!');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = _('O email precisa ter 100 caractéres ou menos!');
            }
    
            if(!$this->subject) {
                $errors['subject'] = _('O assunto é obrigatório!');
            } elseif(strlen($this->subject) > 100) {
                $errors['subject'] = _('O assunto precisa ter 100 caractéres ou menos!');
            }
    
            if(!$this->message) {
                $errors['message'] = _('A mensagem é obrigatória!');
            } elseif(strlen($this->message) > 1000) {
                $errors['message'] = _('A mensagem precisa ter 1000 caractéres ou menos!');
            }
    
            if(!$this->recaptchaResponse->isSuccess()) {
                $errors['recaptcha'] = _('O teste do ReCaptcha falhou! Tente novamente.');
            }
    
            if(count($errors) > 0) {
                throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            }

            $email = new Email();
            if(!$email->add($this->subject, $this->message, $this->name, ERROR_MAIL)->send($this->name, $this->email)) {
                throw $email->error();
            }
        } catch(AppException $e) {
            $this->error = $e;
            return false;
        }

        return true;
    }

    public function error(): ?AppException 
    {
        return $this->error;
    }
}