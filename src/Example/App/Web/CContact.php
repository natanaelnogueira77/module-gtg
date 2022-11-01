<?php

namespace Src\Example\App\Web;

use Src\Example\App\Web\CTemplate;
use Src\Core\Support\Contact;
use Src\Core\Support\Email;

class CContact extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $contactData = [];
        $exception = null;
        $errors = [];

        if(isset($data['contato_nome'])) {
            $contactData = $data;
            try {
                $contact = new Contact($data);
                $contact->checkContact();

                $email = new Email();
                $email->add(
                    $contact->contato_assunto, 
                    $contact->contato_mensagem, 
                    $contact->contato_nome, 
                    MAIN['error_mail']
                );
                $email->send($contact->contato_nome, $contact->contato_email);
                addSuccessMsg('Mensagem enviada com sucesso!');
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }
        
        $this->loadView('page', [
            'title' => 'Contato | ' . SITE,
            'page' => [
                'bg_color' => '#6DB3F2',
                'title' => [
                    'text' => 'Contato',
                    'animation' => ['effect' => 'bounceIn', 'delay' => '.2s']
                ],
                'subtitle' => [
                    'text' => 'Para entrar em contato com a Equipe de Suporte do gtg Software para maiores 
                        esclarecimentos, preencha o formulário abaixo com seu Nome, Email, Assunto e Mensagem e clique em Enviar. Seu Feedback 
                        é altamente apreciado.',
                    'animation' => ['effect' => 'bounceIn', 'delay' => '.5s']
                ]
            ],
            'template' => $this->getTemplateView('contact', $contactData + [
                'errors' => $errors
            ]),
            'exception' => $exception
        ]);
    }
}