<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Web\Template;
use Src\Components\Contact;
use Src\Components\Email;
use Src\Exceptions\ValidationException;

class CContact extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();

        $lang = getLang()->setFilepath('controllers/web/contact')->getContent()->setBase('index');
        $contactData = [];
        $errors = [];
        $exception = null;

        if(isset($data['subject']) || isset($data['message']) || isset($data['name']) || isset($data['email'])) {
            $contactData = $data;
            try {
                $contact = new Contact($data['subject'], $data['message'], $data['name'], $data['email'], $data['g-recaptcha-response']);
                if(!$contact->send()) {
                    throw $contact->error();
                }

                addSuccessMsg($lang->get('success'));
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