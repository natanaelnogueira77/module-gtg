<?php

namespace Src\Models;

use DateTime;
use GTG\MVC\DB\DBModel;
use Src\Models\User;

class UserMeta extends DBModel 
{
    const LANG_KEY = 'lang';
    const LAST_PASS_REQUEST_KEY = 'last_pass_request';

    public $user;

    public static function tableName(): string 
    {
        return 'usuario_meta';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return ['usu_id', 'meta', 'value'];
    }

    public function rules(): array 
    {
        return [
            'meta' => [
                [self::RULE_REQUIRED, 'message' => _('O metadado é obrigatório!')],
                [self::RULE_MAX, 'max' => 50, 'message' => sprintf(_('O metadado deve conter no máximo %s caractéres!'), 50)]
            ],
            self::RULE_RAW => [
                function ($object) {
                    if(!$object->hasError('meta')) {
                        if($object->meta == self::LANG_KEY) {
                            if(!$object->value) {
                                $object->addError(self::LANG_KEY, _('A linguagem é obrigatória!'));
                            }
                        } elseif($object->meta == self::LAST_PASS_REQUEST_KEY) {
                            if(!$object->value) {
                                $object->addError(self::LAST_PASS_REQUEST_KEY, _('A data da última alteração de senha é obrigatória!'));
                            } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $object->value)) {
                                $object->addError(self::LAST_PASS_REQUEST_KEY, _('A data da última alteração de senha deve seguir o padrão dd/mm/aaaa hh:mm:ss!'));
                            }
                        }
                    }
                }
            ]
        ];
    }

    public function user(string $columns = '*'): ?User
    {
        $this->user = $this->belongsTo(User::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->user;
    }
}