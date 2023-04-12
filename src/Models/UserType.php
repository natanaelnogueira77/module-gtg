<?php

namespace Src\Models;

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
    public $users = [];

    public function users(array $filters = [], string $columns = '*'): ?array
    {
        $this->users = $this->hasMany(User::class, 'utip_id', 'id', $filters, $columns)->fetch(true);
        return $this->users;
    }

    public function destroy(): bool 
    {
        if((new User())->get(['utip_id' => $this->id])->count()) {
            throw new AppException(_('Você não pode excluir um tipo de usuário vinculado à um usuário!'));
        }
        return parent::destroy();
    }

    protected function validate(): void 
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