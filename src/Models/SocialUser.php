<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\User;

class SocialUser extends Model 
{
    protected static $tableName = 'social_usuario';
    protected static $hasTimestamps = true;
    protected static $columns = [
        'usu_id',
        'social_id',
        'email',
        'social'
    ];
    protected static $required = [
        'usu_id',
        'social_id',
        'email',
        'social'
    ];
    public $user;

    public function user(string $columns = '*'): ?User
    {
        $this->user = $this->belongsTo(User::class, 'usu_id', 'id', $columns)->fetch(false);
        return $this->user;
    }

    public static function withUser(array $objects, array $filters = [], string $columns = '*'): array
    {
        return self::withBelongsTo($objects, User::class, 'usu_id', 'user', 'id', $filters, $columns);
    }

    public static function getByUserId(int $userId, string $columns = '*'): ?array 
    {
        return (new self())->get(['usu_id' => $userId], $columns)->fetch(true);
    }

    public static function getBySocialId(string $socialId, string $social): ?self 
    {
        return (new self())->get([
            'social_id' => $socialId,
            'social' => $social
        ])->fetch(false);
    }

    public static function getBySocialEmail(string $email, string $social): ?self 
    {
        return (new self())->get([
            'email' => $email,
            'social' => $social
        ])->fetch(false);
    }

    public static function getSocialNames(): array 
    {
        return ['facebook', 'google'];
    }

    protected function validate(): bool 
    {
        $errors = [];
        
        if(!$this->usu_id) {
            $errors['usu_id'] = _('O usuário é obrigatório!');
        }

        if(!$this->social_id) {
            $errors['social_id'] = _('O ID da rede social é obrigatório!');
        }

        if(!$this->social) {
            $errors['social'] = _('O nome da rede social é obrigatório!');
        } elseif(!in_array($this->social, self::getSocialNames())) {
            $errors['social'] = _('O nome da rede social é inválido!');
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

        if(count($errors) > 0) {
            $this->error = new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
            return false;
        }

        return true;
    }
}