<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\Model;

class Config extends Model 
{
    protected static $tableName = 'config';
    protected static $columns = [
        'meta',
        'value'
    ];
    protected static $required = [
        'meta'
    ];
    protected static $metaInfo = [
        'class' => self::class,
        'meta' => 'meta',
        'value' => 'value'
    ];

    protected static $metas = [
        'login_img',
        'logo',
        'logo_icon',
        'style'
    ];

    protected function validate(): void 
    {
        $errors = [];

        if($this->meta == 'login_img') {
            if(!$this->value) {
                $errors['login_img'] = _('A imagem de fundo do login é obrigatória!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['login_img'] = _('A imagem de fundo não é uma imagem válida!');
            }
        } elseif($this->meta == 'logo') {
            if(!$this->value) {
                $errors['logo'] = _('O logo é obrigatório!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['logo'] = _('O logo não é uma imagem válida!');
            }
        } elseif($this->meta == 'logo_icon') {
            if(!$this->value) {
                $errors['logo_icon'] = _('O ícone é obrigatório!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['logo_icon'] = _('O ícone não é uma imagem válida!');
            }
        } elseif($this->meta == 'style') {
            if(!$this->value) {
                $errors['style'] = _('O tema é obrigatório!');
            } elseif(!in_array($this->value, ['light', 'dark'])) {
                $errors['style'] = _('O tema é inválido!');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}