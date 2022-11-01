<?php

namespace Src\Core\Models;

use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\TConfig;

class Config extends Model 
{
    protected static $tableName = 'config';
    protected static $primaryKey = 'id';
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
        'title',
        'style',
        'logo',
        'logo_icon'
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

        if(!$this->title) {
            $errors['title'] = 'Título do Sistema é um campo obrigatório!';
        }

        if(!$this->style) {
            $errors['style'] = 'Por favor, escolha um tema de cores!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}