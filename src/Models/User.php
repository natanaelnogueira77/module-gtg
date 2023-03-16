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
        $this->slug = is_string($this->slug) ? slugify($this->slug) : null;
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;
        
        $this->validate();

        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return parent::save();
    }

    public function userMetas(string $columns = '*'): ?array 
    {
        if(!$this->userMetas) {
            $this->userMetas = $this->hasMany('Src\Models\UserMeta', 'usu_id', 'id', $columns);
        }
        return $this->userMetas;
    }

    public function userType(string $columns = '*'): ?UserType 
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
        return $this->utip_id == 1;
    }

    public static function getBySlug(string $slug): ?self 
    {
        return (new self())->get(['slug' => $slug])->fetch(false);
    }

    public static function getByEmail(string $email): ?self 
    {
        return (new self())->get(['email' => $email])->fetch(false);
    }

    public static function getByToken(string $token): ?self 
    {
        return (new self())->get(['token' => $token])->fetch(false);
    }

    public static function countUsers(): array 
    {
        $array = [];

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
        return $this->password ? password_verify($password, $this->password) : false;
    }

    private function validate(): void 
    {
        $errors = [];
        
        if(!$this->name) {
            $errors['name'] = _('O nome é obrigatório!');
        } elseif(strlen($this->name) > 100) {
            $errors['name'] = _('O nome precisa ter 100 caractéres ou menos!');
        }

        if(!$this->email) {
            $errors['email'] = _('O email é obrigatório!');
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = _('O email é inválido!');
        } elseif(strlen($this->email) > 100) {
            $errors['email'] = _('O email precisa ter 100 caractéres ou menos!');
        } else {
            $email = $this->id 
                ? (new self())
                    ->find('email = :email AND id != :id', "email={$this->email}&id={$this->id}")
                    ->count()
                : (new self())
                    ->find('email = :email', "email={$this->email}")
                    ->count();

            if($email) {
                $errors['email'] = _('O email informado já está em uso! Tente outro.');
            }
        }

        if(!$this->slug) {
            $errors['slug'] = _('O apelido é obrigatório!');
        } elseif(strlen($this->slug) > 100) {
            $errors['slug'] = _('O apelido precisa ter 100 caractéres ou menos!');
        } else {
            $slug = $this->id 
                ? (new self())
                    ->find('slug = :slug AND id != :id', "slug={$this->slug}&id={$this->id}")
                    ->count()
                : (new self())
                    ->find('slug = :slug', "slug={$this->slug}")
                    ->count();
            
            if($slug) {
                $errors['slug'] = _('O apelido informado já está em uso! Tente outro.');
            }
        }

        if(!$this->utip_id) {
            $errors['utip_id'] = _('O tipo de usuário é obrigatório!');
        }

        if(!$this->password) {
            $errors['password'] = _('A senha é obrigatória!');
        } elseif(strlen($this->password) < 5) {
            $errors['password'] = _('A senha deve conter 5 caracteres ou mais!');
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}