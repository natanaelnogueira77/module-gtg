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

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public function user(string $columns = '*'): ?User
    {
        $this->user = (new User())->findById($this->usu_id, $columns);
        return $this->user;
    }

    private function validate(): void 
    {
        $errors = [];

        if($this->meta == 'lang') {
            if(!$this->value) {
                $errors['value'] = _('A linguagem é obrigatória!');
            }
        } elseif($this->meta == 'last_pass_request') {
            if(!$this->value) {
                $errors['value'] = _('A data da última alteração de senha é obrigatória!');
            } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $this->value)) {
                $errors['value'] = _('A data da última alteração de senha deve seguir o padrão dd/mm/aaaa hh:mm:ss!');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}