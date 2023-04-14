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
    protected static $metaInfo = [
        'class' => UserMeta::class,
        'entity' => 'usu_id',
        'meta' => 'meta',
        'value' => 'value'
    ];
    public $socialUser;
    public $userMetas = [];
    public $userType;

    public function save(): bool 
    {
        $this->slug = is_string($this->slug) ? slugify($this->slug) : null;
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;

        return parent::save();
    }

    public function encode(): static 
    {
        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return $this;
    }

    public function socialUser(string $columns = '*'): ?SocialUser 
    {
        $this->socialUser = $this->hasOne(SocialUser::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->socialUser;
    }

    public function userMetas(array $filters = [], string $columns = '*'): ?array 
    {
        $this->userMetas = $this->hasMany(UserMeta::class, 'usu_id', 'id', $filters, $columns)->fetch(true);
        return $this->userMetas;
    }

    public function userType(string $columns = '*'): ?UserType 
    {
        $this->userType = $this->belongsTo(UserType::class, 'utip_id', 'id', $columns)->fetch(false);
        return $this->userType;
    }

    public static function withSocialUser(array $objects, array $filters = [], string $columns = '*'): array
    {
        return self::withHasOne($objects, UserType::class, 'usu_id', 'socialUser', 'id', $filters, $columns);
    }

    public static function withUserType(array $objects, array $filters = [], string $columns = '*'): array
    {
        return self::withBelongsTo($objects, UserType::class, 'utip_id', 'userType', 'id', $filters, $columns);
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

    protected function validate(): bool 
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
            $this->error = new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            return false;
        }

        return true;
    }
}