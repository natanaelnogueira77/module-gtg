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

        $lang = getLang()->setFilepath('models/user-type')->getContent()->setBase('validate');
        
        if(!$this->name_sing) {
            $errors['name_sing'] = $lang->get('name_sing.required');
        } elseif(strlen($this->name_sing) > 45) {
            $errors['name_sing'] = $lang->get('name_sing.max');
        }

        if(!$this->name_plur) {
            $errors['name_plur'] = $lang->get('name_plur.required');
        } elseif(strlen($this->name_plur) > 45) {
            $errors['name_plur'] = $lang->get('name_plur.max');
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, $lang->get('error_message'));
        }
    }
}