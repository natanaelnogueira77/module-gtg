<?php

namespace Src\Models\AR;

use DateTime;
use Src\Models\AR\ActiveRecord;

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
            $this->createRule()->required('meta')->setMessage(_('The metadata is required!')),
            $this->createRule()->maxLength('meta', 50)->setMessage(sprintf(_('The metadata must have %s characters or less!'), 50)),
            $this->createRule()->raw(function($model) {
                if(!$model->hasError('meta')) {
                    if($model->meta == self::KEY_LANG) {
                        if(!$model->value) {
                            $model->addError(
                                self::KEY_LANG, 
                                _('The language is required!')
                            );
                        }
                    } elseif($model->meta == self::KEY_LAST_PASS_REQUEST) {
                        if(!$model->value) {
                            $model->addError(
                                self::KEY_LAST_PASS_REQUEST, 
                                _('The last password reset date is required!')
                            );
                        } elseif(!DateTime::createFromFormat('Y-m-d H:i:s', $model->value)) {
                            $model->addError(
                                self::KEY_LAST_PASS_REQUEST, 
                                _('The last password reset date must follow the dd/mm/aaaa hh:mm:ss pattern!')
                            );
                        }
                    }
                }
            })
        ];
    }
}