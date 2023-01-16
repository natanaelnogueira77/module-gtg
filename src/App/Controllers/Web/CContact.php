<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Web\Template;
use Src\Components\Contact;
use Src\Components\Email;

class CContact extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();

        $contactData = [];
        $errors = [];
        $exception = null;

        if(isset($data['subject']) || isset($data['message']) || isset($data['name']) || isset($data['email'])) {
            $contactData = $data;
            try {
                $contact = new Contact($data['subject'], $data['message'], $data['name'], $data['email']);
                $contact->check();

                $email = new Email();
                $email->add($contact->subject, $contact->message, $contact->name, ERROR_MAIL)
                    ->send($contact->name, $contact->email);

                if($email->error()) {
                    $this->throwException($email->error()->getMessage());
                }

                addSuccessMsg('Mensagem enviada com sucesso!');
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }
        
        $this->loadView('web/contact', $contactData + [
            'errors' => $errors,
            'exception' => $exception
        ]);
    }
}