<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\Model;

class UserType extends Model 
{
    protected static $tableName = 'usuario_tipo';
    protected static $hasTimestamps = true;
    protected static $columns = [
        'name_sing',
        'name_plur'
    ];
    protected static $required = [
        'name_sing',
        'name_plur'
    ];
    public $users;

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public function users(string $columns = '*') 
    {
        if(!$this->users) {
            $this->users = $this->hasMany('Src\Models\User', 'utip_id', 'id', $columns);
        }
        return $this->users;
    }

    public static function withUsers(array $objects = [], string $columns = '*'): array
    {
        if(count($objects) > 0) {
            $objects = self::getGroupedBy($objects);
            $users = (new User())->get([], $columns)->fetch(true);
            foreach($users as $user) {
                $objects[$user->utip_id]->users[] = $user;
            }
        }

        return $objects;
    }

    private function validate(): void 
    {
        $errors = [];
        
        if(!$this->name_sing) {
            $errors['name_sing'] = 'O Nome no Singular é obrigatório!';
        }

        if(!$this->name_plur) {
            $errors['name_plur'] = 'O Nome no Plural é obrigatório!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}