<?php

namespace Src\Models;

use DateTime;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\User;

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

    public function users(string $columns = '*'): ?array
    {
        if(!$this->users) {
            $this->users = $this->hasMany('Src\Models\User', 'utip_id', 'id', $columns);
        }
        return $this->users;
    }

    public static function withUsers(
        array $objects = [], 
        array $filters = [], 
        string $columns = '*'
    ): array
    {
        if(count($objects) > 0) {
            $objects = self::getGroupedBy($objects);
            $users = (new User())->get($filters, $columns)->fetch(true);
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
            $errors['name_sing'] = _('O nome no singular é obrigatório!');
        } elseif(strlen($this->name_sing) > 45) {
            $errors['name_sing'] = _('O nome no singular precisa ter 45 caractéres ou menos!');
        }

        if(!$this->name_plur) {
            $errors['name_plur'] = _('O nome no plural é obrigatório!');
        } elseif(strlen($this->name_plur) > 45) {
            $errors['name_plur'] = _('O nome no plural precisa ter 45 caractéres ou menos!');
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}