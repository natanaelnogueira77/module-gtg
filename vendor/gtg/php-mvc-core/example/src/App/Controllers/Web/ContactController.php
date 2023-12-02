<?php

namespace GTG\MVC\Example\Src\App\Controllers\Web;

use GTG\MVC\Example\Src\App\Controllers\Web\TemplateController;
use Src\Models\ContactForm;

class ContactController extends TemplateController 
{
    public function index(array $data): void 
    {
        $this->addData();

        $contactForm = new ContactForm();
        if($this->request->isPost()) {
            $contactForm->loadData([
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'body' => $data['body']
            ]);
            
            if($contactForm->send()) {
                $this->session->setFlash('success', 'Your message was successfully sent!');
                $this->redirect('contact.index');
            } else {
                $this->session->setFlash('error', 'Validation errors! Check the fields.');
            }
        }
        
        $this->render('web/contact', [
            'contactForm' => $contactForm
        ]);
    }
}