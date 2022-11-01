<?php

namespace Src\Core\Models;

use DateTime;
use Exception;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\LogException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\TModel;
use Src\Core\Support\Email;

class EmailTemplate extends Model 
{
    protected static $tableName = 'email_template';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'usu_id',
        'name',
        'subject',
        'content',
        'meta',
        'date_c',
        'date_m'
    ];
    protected static $required = [
        'usu_id',
        'name',
        'subject',
        'content',
        'meta',
        'date_c',
        'date_m'
    ];
    protected static $jsonValues = [];

    use TModel;

    public function save(): bool 
    {
        $this->date_c = $this->date_c 
            ? (new DateTime($this->date_c))->format('Y-m-d H:i') 
            : date('Y-m-d H:i');
        $this->date_m = date('Y-m-d H:i');

        $this->validate();
        return parent::save();
    }

    public static function getByMeta(string $meta = ''): ?EmailTemplate 
    {
        $email = self::getOne(['meta' => $meta]);
        if($email) $email->decodeContent();

        return $email;
    }

    public function decodeContent(): EmailTemplate
    {
        $this->content = urldecode($this->content);
        return $this;
    }

    // Substitui as MergeTags do e-mail por informações do usuário, se houver
    public function transformEmail(array $params = array()): array
    {
        $subject = self::mergeTagEmail($this->subject, $params);
        $content = self::mergeTagEmail($this->content, $params);

        return [
            'subject' => $subject,
            'content' => $content
        ];
    }

    public static function mergeTagEmail(string $html = '', array $params = array()): ?string
    {
        if(count($params) > 0) {
            foreach($params as $key => $value) {
                $html = str_replace('{' . $key . '}', $value, $html);
            }
        }

        $html = str_replace('{title}', SITE, $html);
        $html = str_replace('{path}', url(), $html);

        return html_entity_decode($html);
    }

    public function sendParams(string $to, string $name, array $params = array()): void
    {
        try {
            $content = $this->transformEmail($params);

            $subject = $content['subject'];
            $message = $content['content'];
            $headers['to_name'] = $name;

            if($bccEmail) $headers['bcc'] = $bccEmail;
            $this->send($to, $subject, $message, $headers);
        } catch(Exception $e) {
            throw new LogException($e->getMessage(), 'Ocorreu um erro ao enviar o E-mail!');
        }
    }

    public function send(
        string $to, 
        string $subject, 
        string $message, 
        array $headers, 
        array $attachments = []
    ): void
    {
        $email = new Email();

        $email->add($subject, $message, $headers['to_name'], $to);
        
        if($headers['bcc']) $email->bcc($headers('bcc'));

        if($attachments) {
            foreach($attachments as $attach) {
                $email->attach($attach['path'], $attach['name']);
            }
        }

        $email->send();
        if($email->error()) {
            throw new Exception($email->error()->getMessage());
        }
    }

    private function validate(): void 
    {
        $errors = [];

        if(!$this->name) {
            $errors['name'] = 'Por favor, coloque um nome para o Template de E-mail!';
        }

        if(!$this->meta) {
            $errors['meta'] = 'O Template de E-mail precisa ter uma chave!';
        }

        if(!$this->subject) {
            $errors['subject'] = 'O Template de E-mail precisa ter um assunto!';
        }

        if(!$this->content) {
            $errors['content'] = 'Por favor, coloque alguma mensagem!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}