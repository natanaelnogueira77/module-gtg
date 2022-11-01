<?php

namespace Src\Core\Support;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;

class Contact 
{
    public $contato_nome = '';
    public $contato_email = '';
    public $contato_assunto = '';
    public $contato_mensagem = '';

    public function __construct(array $arr = []) 
    {
        $this->contato_nome = $arr['contato_nome'];
        $this->contato_email = $arr['contato_email'];
        $this->contato_assunto = $arr['contato_assunto'];
        $this->contato_mensagem = $arr['contato_mensagem'];
    }

    public function validate(): void
    {
        $errors = [];

        if(!$this->contato_nome) {
            $errors['contato_nome'] = 'Nome é um campo obrigatório!';
        }
    
        if(!$this->contato_email) {
            $errors['contato_email'] = 'Email é um campo obrigatório!';
        } elseif(!filter_var($this->contato_email, FILTER_VALIDATE_EMAIL)) {
            $errors['contato_email'] = 'Email inválido!';
        }
        
        if(!$this->contato_assunto) {
            $errors['contato_assunto'] = 'Informe o assunto de sua mensagem!';
        }
    
        if(!$this->contato_mensagem) {
            $errors['contato_mensagem'] = 'Escreva algo em sua mensagem!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function validateEmailToUsers(): void
    {
        $errors = [];

        if(!$this->mail_all && $this->mail_user == '') {
            $errors['mail_user'] = 'Informe o assunto de sua mensagem!';
        }
        
        if(!$this->mail_assunto) {
            $errors['mail_assunto'] = 'Informe o assunto de sua mensagem!';
        }
    
        if(!$this->mail_mensagem) {
            $errors['mail_mensagem'] = 'Escreva algo em sua mensagem!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
    
    public function checkContact(): array 
    {
        $this->validate();
        $contato = [
            'contato_nome' => $this->contato_nome, 
            'contato_email' => $this->contato_email, 
            'contato_assunto' => $this->contato_assunto, 
            'contato_mensagem' => $this->contato_mensagem
        ];
        
        if($contato) {
            return $contato;
        }
    }
}