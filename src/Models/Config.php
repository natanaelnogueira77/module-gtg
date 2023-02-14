<?php

namespace Src\Models;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\TConfig;

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
    protected static $elements = [
        'meta' => 'meta',
        'value' => 'value'
    ];
    protected static $metas = [
        'style',
        'logo',
        'logo_icon',
        'login_img'
    ];
    protected static $jsonValues = [];
    protected static $handlers = [];

    use TConfig;

    public function save(): bool 
    {
        $this->validate();
        $this->encodeJSON();
        return parent::save();
    }

    private function validate(): void 
    {
        $errors = [];

        if(!$this->style) {
            $errors['style'] = _('O Estilo é obrigatório!');
        } elseif(!in_array($this->style, ['light', 'dark'])) {
            $errors['style'] = _('O Estilo é inválido!');
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de Validação! Verifique os campos.'));
        }
    }
}