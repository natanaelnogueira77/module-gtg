<?php

namespace Models\AR;

use DateTime;

class UserMeta extends ActiveRecord 
{
    public const KEY_LANG = 'lang';
    public const KEY_LAST_PASS_REQUEST = 'last_pass_request';

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
        return [
            'usu_id', 
            'meta', 
            'value'
        ];
    }
        
    public static function hasTimestamps(): bool 
    {
        return false;
    }

    public function rules(): array 
    {
        return [
            $this->createRule()->required('meta')->setMessage(_('O metadado é obrigatório!')),
            $this->createRule()->maxLength('meta', 50)->setMessage(sprintf(_('O metadado deve ter %s caractéres ou menos!'), 50)),
            $this->createRule()->raw(function($model) {
                if(!$model->hasError('meta')) {
                    if($model->meta == self::KEY_LANG) {
                        if(!$model->value) {
                            $model->addError(
                                self::KEY_LANG, 
                                _('A linguagem é obrigatória!')
                            );
                        }
                    } elseif($model->meta == self::KEY_LAST_PASS_REQUEST) {
                        if(!$model->value) {
                            $model->addError(
                                self::KEY_LAST_PASS_REQUEST, 
                                _('A data da última solicitação de redefinição de senha é obrigatória!')
                            );
                        } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $model->value)) {
                            $model->addError(
                                self::KEY_LAST_PASS_REQUEST, 
                                _('A data da última solicitação de redefinição de senha deve seguir o padrão dd/mm/aaaa hh:mm:ss!')
                            );
                        }
                    }
                }
            })
        ];
    }
}