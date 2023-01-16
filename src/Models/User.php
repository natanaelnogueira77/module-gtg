<?php

namespace Src\Models;

use DateTime;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\UserMeta;
use Src\Models\UserType;

class User extends Model 
{
    protected static $tableName = 'usuario';
    protected static $hasTimestamps = true;
    protected static $columns = [
        'utip_id',
        'name',
        'email',
        'password',
        'token',
        'slug'
    ];
    protected static $required = [
        'utip_id',
        'name',
        'email',
        'password',
        'token',
        'slug'
    ];
    public $userType;
    public $userMetas;

    public function save(): bool 
    {
        $this->slug = is_string($this->slug) ? generateSlug($this->slug) : null;
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;
        
        $this->validate();

        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return parent::save();
    }

    public function userMetas(string $columns = '*') 
    {
        if(!$this->userMetas) {
            $this->userMetas = $this->hasMany('Src\Models\UserMeta', 'usu_id', 'id', $columns);
        }
        return $this->userMetas;
    }

    public function userType(string $columns = '*') 
    {
        if(!$this->userType) {
            $this->userType = $this->belongsTo('Src\Models\UserType', 'utip_id', 'id', $columns);
        }
        return $this->userType;
    }

    public static function withUserType(
        array $objects = [], 
        array $filters = [], 
        string $columns = '*'
    ): array
    {
        if(count($objects) > 0) {
            $ids = self::getPropertyValues($objects, 'utip_id');

            $userTypes = (new UserType())->get([
                'in' => ['id' => $ids]
            ] + $filters, $columns)->fetch(true);
            if($userTypes) {
                $userTypes = UserType::getGroupedBy($userTypes, 'id');
                foreach($objects as $index => $object) {
                    $objects[$index]->userType = $userTypes[$object->utip_id];
                }
            }
        }

        return $objects;
    }

    public function isAdmin(): bool
    {
        if($this->utip_id == 1) {
            return true;
        }

        return false;
    }

    public static function getBySlug(string $slug): ?self 
    {
        $user = (new self())->get(['slug' => $slug])->fetch(false);
        return $user;
    }

    public static function getByEmail(string $email): ?self 
    {
        $user = (new self())->get(['email' => $email])->fetch(false);
        return $user;
    }

    public static function getByToken(string $token): ?self 
    {
        $user = (new self())->get(['token' => $token])->fetch(false);
        return $user;
    }

    public static function countUsers(): array 
    {
        $array = [];

        $model = new self();
        $results = (new self())->find(null, null, 'COUNT(*) as count, utip_id')
            ->group('utip_id')->fetch('count');

        if($results) {
            foreach($results as $result) {
                $array[$result->utip_id] = $result->data->count;
            }
        }

        return $array;
    }

    public function verifyPassword(string $password): bool 
    {
        if(!$this->password) {
            return false;
        }
        return password_verify($password, $this->password);
    }

    private function validate(): void 
    {
        $errors = [];
        
        if(!$this->name) {
            $errors['name'] = 'O Nome é obrigatório!';
        }

        if(!$this->email) {
            $errors['email'] = 'O Email é obrigatório!';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'O Email é inválido!';
        } else {
            if(!$this->id) {
                $email = (new self())
                    ->find('email = :email', "email={$this->email}")
                    ->count();
            } else {
                $email = (new self())
                    ->find('email = :email AND id != :id', "email={$this->email}&id={$this->id}")
                    ->count();
            }

            if($email) {
                $errors['email'] = 'Este Email já está em uso! Tente outro.';
            }
        }

        if(!$this->slug) {
            $errors['slug'] = 'O Apelido é obrigatório!';
        } else {
            if(!$this->id) {
                $slug = (new self())
                    ->find('slug = :slug', "slug={$this->slug}")
                    ->count();
            } else {
                $slug = (new self())
                    ->find('slug = :slug AND id != :id', "slug={$this->slug}&id={$this->id}")
                    ->count();
            }
            
            if($slug) {
                $errors['slug'] = 'Este Apelido já está em uso! Tente outro.';
            }
        }

        if(!$this->utip_id) {
            $errors['utip_id'] = 'O Tipo de Usuário é obrigatório!';
        }

        if(!$this->password) {
            $errors['password'] = 'A Senha é obrigatória!';
        } elseif(strlen($this->password) < 5) {
            $errors['password'] = 'A Senha deve conter 5 caracteres ou mais!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}