<?php

namespace Src\Core\Models;

use DateTime;
use Exception;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\UserMeta;
use Src\Core\Models\UserType;
use Src\Core\Models\TModel;

class User extends Model 
{
    protected static $tableName = 'usuario';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'utip_id',
        'name',
        'email',
        'password',
        'token',
        'slug',
        'date_c',
        'date_m'
    ];
    protected static $required = [
        'utip_id',
        'name',
        'email',
        'password',
        'token',
        'slug',
        'date_c',
        'date_m'
    ];
    protected static $jsonValues = [];

    use TModel;

    public function save(): bool 
    {
        $this->date_c = $this->date_c 
            ? (new DateTime($this->date_c))->format('Y-m-d') 
            : date('Y-m-d');
        $this->date_m = date('Y-m-d');

        $this->slug = is_string($this->slug) ? generateSlug($this->slug) : null;
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;
        
        $this->validate();

        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return parent::save();
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function isAdmin(): bool
    {
        if($this->utip_id == 1) {
            return true;
        }

        return false;
    }

    public static function getBySlug(string $slug): ?User 
    {
        $user = self::getOne(['slug' => $slug]);
        return $user;
    }

    public static function getByEmail(string $email): ?User 
    {
        $user = self::getOne(['email' => $email]);
        return $user;
    }

    public static function countUsers(): array 
    {
        $array = [];

        $model = new self();
        $results = $model->find(null, null, 'COUNT(*) as count, utip_id')
            ->group('utip_id')
            ->fetch('count');

        if($results) {
            foreach($results as $result) {
                $array[$result->utip_id] = $result->data->count;
            }
        }

        return $array;
    }

    private function validate(): void 
    {
        $errors = [];
        
        if(!$this->name) {
            $errors['name'] = 'Nome é um dado obrigatório.';
        }

        if(!$this->email) {
            $errors['email'] = 'Email é um dado obrigatório.';
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        } else {
            if(!$this->id) {
                $email = (new self())->find('email = :email', "email={$this->email}")->count();
            } else {
                $email = (new self())
                    ->find('email = :email AND id != :id', "email={$this->email}&id={$this->id}")
                    ->count();
            }

            if($email) {
                $errors['email'] = 'O E-mail informado já está em uso!';
            }
        }

        if(!$this->slug) {
            $errors['slug'] = 'Apelido é um dado obrigatório.';
        } else {
            if(!$this->id) {
                $slug = (new self())->find('slug = :slug', "slug={$this->slug}")->count();
            } else {
                $slug = (new self())
                    ->find('slug = :slug AND id != :id', "slug={$this->slug}&id={$this->id}")
                    ->count();
            }
            
            if($slug) {
                $errors['slug'] = 'O apelido informado já está em uso! Tente algo diferente.';
            }
        }

        if(!$this->utip_id) {
            $errors['utip_id'] = 'Por favor, selecione um nível para esse usuário!';
        }

        if(!$this->date_c) {
            $errors['date_c'] = 'Data de Registro é um dado obrigatório.';
        } elseif(!DateTime::createFromFormat('Y-m-d', $this->date_c)) {
            $errors['date_c'] = 'Data de Registro deve seguir o padrão dd/mm/aaaa.';
        }

        if(!$this->date_m) {
            $errors['date_m'] = 'Data de Modificação é um dado obrigatório.';
        } elseif(!DateTime::createFromFormat('Y-m-d', $this->date_m)) {
            $errors['date_m'] = 'Data de Modificação deve seguir o padrão dd/mm/aaaa.';
        }

        if(!$this->password) {
            $errors['password'] = 'Senha é um dado obrigatório.';
        } elseif(strlen($this->password) < 5) {
            $errors['password'] = 'Sua senha precisa ter mais de 5 caractéres!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}