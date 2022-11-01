<?php

namespace Src\Core\CRUD;

use Exception;
use Src\Core\Models\EmailTemplate;

class CRUDEmailTemplate 
{
    private $error;

    public function get(int $emailId): ?EmailTemplate 
    {
        $dbEmail = EmailTemplate::getById($emailId);
        if(!$dbEmail) {
            throw new Exception('O usuário não foi encontrado!');
        }

        return $dbEmail;
    }
    
    public function create(array $data): ?EmailTemplate 
    {
        $dbEmail = new EmailTemplate();
        $dbEmail->setValues([
            'usu_id' => $data['usu_id'],
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => urlencode($data['content']),
            'meta' => $data['meta']
        ]);
        $dbEmail->save();
        return $dbEmail;
    }

    public function update(int $emailId, array $data): ?EmailTemplate 
    {
        $dbEmail = EmailTemplate::getById($emailId);
        if(!$dbEmail) {
            throw new Exception('Este E-mail não foi encontrado!');
        }

        $dbEmail->setValues([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => urlencode($data['content']),
            'meta' => $data['meta']
        ]);
        $dbEmail->save();
        return $dbEmail;
    }

    public function delete(int $emailId): ?EmailTemplate 
    {
        $dbEmail = EmailTemplate::getById($emailId);
        if(!$dbEmail) {
            throw new Exception('Nenhum Template de E-mail foi encontrado!');
        }

        $dbEmail->destroy();
        return $dbEmail;
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}