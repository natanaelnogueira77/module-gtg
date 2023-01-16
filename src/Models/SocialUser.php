<?php

namespace Src\Models;

use DateTime;
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

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public function getUser() 
    {
        $this->user = User::getById($this->usu_id);
    }

    public static function getBySocialId(string $socialId, string $social) 
    {
        $object = (new self())->get([
            'social_id' => $socialId,
            'social' => $social
        ])->fetch(false);

        return $object;
    }

    public static function getBySocialEmail(string $email, string $social) 
    {
        $object = (new self())->get([
            'email' => $email,
            'social' => $social
        ])->fetch(false);

        return $object;
    }

    private function validate() 
    {
        $errors = [];
        
        if(!$this->usu_id) {
            $errors['usu_id'] = 'O Usuário é obrigatório!';
        }

        if(!$this->social_id) {
            $errors['social_id'] = 'O ID da Rede Social é obrigatório!';
        }

        if(!$this->social) {
            $errors['social'] = 'O Nome da Rede Social é obrigatório!';
        } elseif(!in_array($this->social, ['facebook', 'google'])) {
            $errors['social'] = 'O Nome da Rede Social é inválido!';
        }

        if(!$this->email) {
            $errors['email'] = 'O Email é obrigatório!';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Este Email é inválido!';
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

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}