<?php

namespace Src\App\Controllers\Web;

use ReflectionClass;
use Src\App\Controllers\Web\Template;
use Src\Components\Contact;
use Src\Exceptions\AppException;

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
                $contact = new Contact(
                    $data['subject'], 
                    $data['message'], 
                    $data['name'], 
                    $data['email'], 
                    $data['g-recaptcha-response'],
                    $_GET['action'],
                    $_SERVER['SERVER_NAME'],
                    $_SERVER['REMOTE_ADDR']
                );
                if(!$contact->send()) {
                    throw $contact->error();
                }

                addSuccessMsg(_('Mensagem enviada com sucesso!'));
            } catch(AppException $e) {
                $exception = $e;
                if((new ReflectionClass($exception))->getShortName() == 'ValidationException') {
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