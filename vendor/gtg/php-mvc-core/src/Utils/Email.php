<?php

namespace GTG\MVC\Utils;

use Exception, stdClass;
use PHPMailer\PHPMailer\PHPMailer;
use GTG\MVC\Application;
use GTG\MVC\Exceptions\EmailException;
use GTG\MVC\SMTP;

class Email 
{
    private PHPMailer $mail;
    private stdClass $data;

    public function __construct(
        private ?SMTP $SMTP = null
    ) 
    {
        $this->SMTP = $SMTP ?? Application::getInstance()->SMTP;
        $this->data = new stdClass();
        $this->setMailOptions();
    }

    private function setMailOptions(): void 
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->setLanguage('br');
        
        $this->mail->SMTPDebug = false;
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->CharSet = 'utf-8';

        $this->mail->Host = $this->SMTP->getHost();
        $this->mail->Port = $this->SMTP->getPort();
        $this->mail->Username = $this->SMTP->getUsername();
        $this->mail->Password = $this->SMTP->getPassword();
    }

    public function add(
        string $subject, 
        string $body, 
        string $recipientName, 
        string $recipientEmail
    ): self 
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_name = $recipientName;
        $this->data->recipient_email = $recipientEmail;

        return $this;
    }

    public function attach(string $filePath, string $fileName): self 
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    public function bcc(string $email): self 
    {
        $this->data->bcc = $email;
        return $this;
    }

    public function send(?string $fromName = null, ?string $fromEmail = null): void 
    {
        try {
            $this->loadMailForSend(
                $fromName ?? $this->SMTP->getFromName(),
                $fromEmail ?? $this->SMTP->getFromEmail()
            );
            $this->mail->send();
        } catch(Exception $e) {
            throw new EmailException('Error when sending: ' . $e->getMessage());
        }
    }

    private function loadMailForSend(string $fromName, string $fromEmail): void 
    {
        $this->mail->Subject = $this->data->subject;
        $this->mail->msgHTML($this->data->body);
        $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
        $this->mail->setFrom($fromEmail, $fromName);

        if(!empty($this->data->bcc)) {
            $this->mail->addBCC($this->data->bcc);
        }

        if(!empty($this->data->attach)) {
            foreach($this->data->attach as $path => $name) {
                $this->mail->addAttachment($path, $name);
            }
        }
    }
}