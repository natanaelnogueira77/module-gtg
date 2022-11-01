<?php

namespace Src\Example\Models;

use DateTime;
use Exception;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\User;
use Src\Core\Models\TModel;

class SocialUser extends Model 
{
    protected static $tableName = 'social_usuario';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'usu_id',
        'social_id',
        'email',
        'social',
        'date_c'
    ];
    protected static $required = [
        'usu_id',
        'social_id',
        'email',
        'social',
        'date_c'
    ];
    protected static $jsonValues = [];

    use TModel;

    public function save(): bool 
    {
        $this->date_c = $this->date_c 
            ? (new DateTime($this->date_c))->format('Y-m-d H:i') 
            : date('Y-m-d H:i');

        $this->validate();
        return parent::save();
    }

    public function getUser() 
    {
        $this->user = User::getById($this->usu_id);
    }

    public static function getBySocialId(string $socialId, string $social) 
    {
        $object = self::getOne([
            'social_id' => $socialId,
            'social' => $social
        ]);

        return $object;
    }

    public static function getBySocialEmail(string $email, string $social) 
    {
        $object = self::getOne([
            'email' => $email,
            'social' => $social
        ]);

        return $object;
    }

    private function validate() 
    {
        $errors = [];
        
        if(!$this->usu_id) {
            $errors['usu_id'] = 'Um usuário não foi relacionado!';
        }

        if(!$this->social_id) {
            $errors['social_id'] = 'O ID da rede precisa ser colocado!';
        }

        if(!$this->social) {
            $errors['social'] = 'O nome da rede precisa ser informado!';
        } elseif(!in_array($this->social, ['facebook', 'google'])) {
            $errors['social'] = 'Rede social inválida!';
        }

        if(!$this->email) {
            $errors['email'] = 'Email é um dado obrigatório.';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        } else {
            if(!$this->id) {
                $email = (new self())->find('email = :email', "email={$this->email}")->count();
            } else {
                $email = (new self())->find('email = :email AND id != :id', 
                    "email={$this->email}&id={$this->id}")->count();
            }

            if($email) {
                $errors['email'] = 'O E-mail informado já está em uso!';
            }
        }

        if(!$this->date_c) {
            $errors['date_c'] = 'Data de Registro é um dado obrigatório.';
        } elseif(!DateTime::createFromFormat('Y-m-d H:i', $this->date_c)) {
            $errors['date_c'] = 'Data de Registro deve seguir o padrão dd/mm/aaaa hh:mm.';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}