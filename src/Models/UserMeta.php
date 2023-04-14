<?php

namespace Src\Models;

use DateTime;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\User;

class UserMeta extends Model 
{
    protected static $tableName = 'usuario_meta';
    protected static $columns = [
        'usu_id',
        'meta',
        'value'
    ];
    protected static $required = [
        'usu_id',
        'meta'
    ];

    protected static $metas = [
        'lang',
        'last_pass_request'
    ];
    public $user;

    public function user(string $columns = '*'): ?User
    {
        $this->user = $this->belongsTo(User::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->user;
    }

    protected function validate(): bool 
    {
        $errors = [];

        if($this->meta == 'lang') {
            if(!$this->value) {
                $errors['lang'] = _('A linguagem é obrigatória!');
            }
        } elseif($this->meta == 'last_pass_request') {
            if(!$this->value) {
                $errors['last_pass_request'] = _('A data da última alteração de senha é obrigatória!');
            } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $this->value)) {
                $errors['last_pass_request'] = _('A data da última alteração de senha deve seguir o padrão dd/mm/aaaa hh:mm:ss!');
            }
        }

        if(count($errors) > 0) {
            $this->error = new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            return false;
        }

        return true;
    }
}