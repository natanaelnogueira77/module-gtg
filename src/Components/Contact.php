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
    
            $lang = getLang()->setFilepath('components/contact')->getContent()->setBase('send');

            if(!$this->name) {
                $errors['name'] = $lang->get('name.required');
            } elseif(strlen($this->name) > 100) {
                $errors['name'] = $lang->get('name.max');
            }
    
            if(!$this->email) {
                $errors['email'] = $lang->get('email.required');
            } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = $lang->get('email.invalid');
            } elseif(strlen($this->email) > 100) {
                $errors['email'] = $lang->get('email.max');
            }
    
            if(!$this->subject) {
                $errors['subject'] = $lang->get('subject.required');
            } elseif(strlen($this->subject) > 100) {
                $errors['subject'] = $lang->get('subject.max');
            }
    
            if(!$this->message) {
                $errors['message'] = $lang->get('message.required');
            } elseif(strlen($this->message) > 1000) {
                $errors['message'] = $lang->get('message.max');
            }
    
            if(!$this->recaptchaResponse->isSuccess()) {
                $errors['recaptcha'] = $lang->get('recaptcha_error');
            }
    
            if(count($errors) > 0) {
                throw new ValidationException($errors, $lang->get('error_message'));
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