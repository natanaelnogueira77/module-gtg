<?php

namespace Src\Core\Models;

use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\TModel;

class UserType extends Model 
{
    protected static $tableName = 'usuario_tipo';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'name_sing',
        'name_plur'
    ];
    protected static $required = [
        'name_sing',
        'name_plur'
    ];
    protected static $jsonValues = [];

    use TModel;

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public static function updateObjects(array $objects = []): void 
    {
        foreach($objects as $object) {
            $object->validate();
        }
        parent::updateObjects($objects);
    }

    private function validate(): void 
    {
        $errors = [];
        
        if(!$this->name_sing) {
            $errors['name_sing'] = 'Nome no Singular é obrigatório!';
        }

        if(!$this->name_plur) {
            $errors['name_plur'] = 'Nome no Plural é obrigatório!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}