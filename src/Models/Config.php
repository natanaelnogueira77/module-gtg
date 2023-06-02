<?php

namespace Src\Models;

use GTG\MVC\DB\DBModel;

class Config extends DBModel 
{
    public static function tableName(): string 
    {
        return 'config';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return ['meta', 'value'];
    }

    public static function metaTableData(): ?array 
    {
        return [
            'class' => self::class,
            'meta' => 'meta',
            'value' => 'value'
        ];
    }

    public function rules(): array 
    {
        return [
            'meta' => [
                [self::RULE_REQUIRED, 'message' => _('O metadado é obrigatório!')],
                [self::RULE_MAX, 'max' => 50, 'message' => sprintf(_('O metadado deve conter no máximo %s caractéres!'), 50)]
            ]
        ];
    }

    public function validate(): bool 
    {
        if(!parent::validate()) {
            return false;
        }

        if($this->meta == 'login_img') {
            if(!$this->value) {
                $this->addError('login_img', _('A imagem de fundo do login é obrigatória!'));
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $this->addError('login_img', _('A imagem de fundo não é uma imagem válida!'));
            }
        } elseif($this->meta == 'logo') {
            if(!$this->value) {
                $this->addError('logo', _('O logo é obrigatório!'));
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $this->addError('logo', _('O logo não é uma imagem válida!'));
            }
        } elseif($this->meta == 'logo_icon') {
            if(!$this->value) {
                $this->addError('logo_icon', _('O ícone é obrigatório!'));
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $this->addError('logo_icon', _('O ícone não é uma imagem válida!'));
            }
        } elseif($this->meta == 'style') {
            if(!$this->value) {
                $this->addError('style', _('O tema é obrigatório!'));
            } elseif(!in_array($this->value, ['light', 'dark'])) {
                $this->addError('style', _('O tema é inválido!'));
            }
        }

        return !$this->hasErrors();
    }
}