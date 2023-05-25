<?php

namespace Src\Models;

use DateTime;
use GTG\MVC\DB\DBModel;
use Src\Models\User;

class UserMeta extends DBModel 
{
    public $user;

    public function tableName(): string 
    {
        return 'usuario_meta';
    }

    public function primaryKey(): string 
    {
        return 'id';
    }

    public function attributes(): array 
    {
        return ['usu_id', 'meta', 'value'];
    }

    public function rules(): array 
    {
        if($this->meta == 'lang') {
            if(!$this->value) {
                $this->addError('lang', _('A linguagem é obrigatória!'));
            }
        } elseif($this->meta == 'last_pass_request') {
            if(!$this->value) {
                $this->addError('last_pass_request', _('A data da última alteração de senha é obrigatória!'));
            } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $this->value)) {
                $this->addError('last_pass_request', _('A data da última alteração de senha deve seguir o padrão dd/mm/aaaa hh:mm:ss!'));
            }
        }

        return [
            'meta' => [
                [self::RULE_REQUIRED, 'message' => _('O metadado é obrigatório!')],
                [self::RULE_MAX, 'max' => 50, 'message' => sprintf(_('O metadado deve conter no máximo %s caractéres!'), 50)]
            ],
            'value' => [
                [self::RULE_REQUIRED, 'message' => _('O valor é obrigatório!')]
            ]
        ];
    }

    public function user(string $columns = '*'): ?User
    {
        $this->user = $this->belongsTo(User::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->user;
    }
}