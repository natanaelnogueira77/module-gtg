<?php

namespace GTG\MVC\Example\Src\Models;

use GTG\MVC\Components\Email;
use GTG\MVC\Model;

class ContactForm extends Model 
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $subject = null;
    public ?string $body = null;

    public function rules(): array 
    {
        return [
            'name' => [
                [self::RULE_REQUIRED, 'message' => 'The name is required!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The name must have %s characters or less!', 100)]
            ],
            'email' => [
                [self::RULE_REQUIRED, 'message' => 'The email is required!'], 
                [self::RULE_EMAIL, 'message' => 'The name is invalid!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The email must have %s characters or less!', 100)]
            ],
            'subject' => [
                [self::RULE_REQUIRED, 'message' => 'The subject is required!'],
                [self::RULE_MAX, 'max' => 100, 'message' => sprintf('The subject must have %s characters or less!', 100)]
            ],
            'body' => [
                [self::RULE_REQUIRED, 'message' => 'The message is required!'],
                [self::RULE_MAX, 'max' => 1000, 'message' => sprintf('The message must have %s characters or less!', 1000)]
            ]
        ];
    }

    public function send(): bool 
    {
        if(!$this->validate()) {
            return false;
        }

        $email = new Email();
        $email->add(
            $this->subject, 
            $this->body, 
            'GTG Software', 
            'contato@gtg.software'
        );
        if(!$email->send($this->name, $this->email)) {
            return false;
        }

        return true;
    }
}