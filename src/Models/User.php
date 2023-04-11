<?php

namespace Src\Models;

use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\SocialUser;
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
    public $userMetas = [];
    public $userType;

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

    public function userMetas(array $filters = [], string $columns = '*'): ?array 
    {
        $this->userMetas = (new UserMeta())->get(['usu_id' => $this->id] + $filters, $columns)->fetch(true);
        return $this->userMetas;
    }

    public function userType(string $columns = '*'): ?UserType 
    {
        $this->userType = (new UserType())->findById($this->utip_id, $columns);
        return $this->userType;
    }

    public static function withUserType(array $objects, array $filters = [], string $columns = '*'): array
    {
        $ids = self::getPropertyValues($objects, 'utip_id');

        $registries = (new UserType())->get(['in' => ['id' => $ids]] + $filters, $columns)->fetch(true);
        if($registries) {
            $registries = UserType::getGroupedBy($registries);
            foreach($objects as $index => $object) {
                $objects[$index]->userType = $registries[$object->utip_id];
            }
        }

        return $objects;
    }

    public function getMeta(string $meta): mixed
    {
        $object = (new UserMeta())->get(['usu_id' => $this->id, 'meta' => $meta])->fetch(false);
        return $object ? $object->value : null;
    }

    public function getGroupedMetas(array $metas): ?array
    {
        $objects = (new UserMeta())->get(['usu_id' => $this->id, 'in' => ['meta' => $metas]])->fetch(true);
        if($objects) {
            $metas = [];
            foreach($objects as $object) {
                $metas[$object->meta] = $object->value;
            }
            return $metas;
        }
        return null;
    }

    public function saveMeta(string $meta, mixed $value): void 
    {
        $object = (new UserMeta())->get(['usu_id' => $this->id, 'meta' => $meta])->fetch(false);
        if(!$object) {
            $object = (new UserMeta());
            $object->usu_id = $this->id;
            $object->meta = $meta;
        }
        $object->value = $value;
        $object->save();
    }

    public function saveManyMetas(array $data): void 
    {
        $objects = (new UserMeta())->get([
            'usu_id' => $this->id,
            'in' => ['meta' => array_keys($data)]
        ])->fetch(true);
        if($objects) {
            $objects = UserMeta::getGroupedBy($objects, 'meta');
        }

        $errors = [];

        foreach($data as $meta => $value) {
            if(isset($objects[$meta])) {
                $objects[$meta]->value = $value;
            } else {
                $objects[$meta] = (new UserMeta())->setValues([
                    'usu_id' => $this->id,
                    'meta' => $meta,
                    'value' => $value
                ]);
            }
            
            try {
                $objects[$meta]->validate();
            } catch(ValidationException $e) {
                $errors = array_merge($errors, $e->getErrors());
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }

        for($i = 0; $i <= count($objects) - 1; $i += 1000) {
            if($objects) {
                parent::updateMany(array_slice($objects, $i, 1000));
            }
        }
    }

    public function isAdmin(): bool
    {
        return $this->utip_id == 1;
    }

    public static function getBySlug(string $slug, string $columns = '*'): ?self 
    {
        return (new self())->get(['slug' => $slug], $columns)->fetch(false);
    }

    public static function getByEmail(string $email, string $columns = '*'): ?self 
    {
        return (new self())->get(['email' => $email], $columns)->fetch(false);
    }

    public static function getByToken(string $token, string $columns = '*'): ?self 
    {
        return (new self())->get(['token' => $token], $columns)->fetch(false);
    }

    public function verifyPassword(string $password): bool 
    {
        return $this->password ? password_verify($password, $this->password) : false;
    }

    public function destroy(): bool 
    {
        if($this->utip_id == 1) {
            throw new AppException(_('Vai por mim, isso vai dar ruim! Você não pode excluir o administrador do sistema.'));
        } elseif((new SocialUser())->get(['usu_id' => $this->id])->count()) {
            throw new AppException(_('Você não pode excluir um usuário vinculado à uma rede social!'));
        } elseif((new UserMeta())->get(['usu_id' => $this->id])->count()) {
            throw new AppException(_('Você não pode excluir um usuário com dados armazenados!'));
        }
        return parent::destroy();
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