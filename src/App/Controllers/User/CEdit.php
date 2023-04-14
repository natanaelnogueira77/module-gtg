<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\User\Template;
use Src\Components\Auth;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class CEdit extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $this->loadView('user/edit', [
            'user' => Auth::get()
        ]);
    }

    public function update(array $data): void 
    {
        $callback = [];
        
        try {
            $user = Auth::get();
            $this->validateUser(['id' => $user->id] + $data);

            $dbUser = new User();
            $dbUser->setValues([
                'id' => $user->id,
                'utip_id' => $user->utip_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['update_password'] ? $data['password'] : $user->password,
                'slug' => $data['slug']
            ])->save();

            Auth::set($dbUser);
            
            $this->setMessage(_('Seus dados foram atualizados com sucesso!'));
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    protected function validateUser(array $data): void 
    {
        $errors = [];
        
        if(!$data['name']) {
            $errors['name'] = _('O nome é obrigatório!');
        } elseif(strlen($data['name']) > 100) {
            $errors['name'] = _('O nome precisa ter 100 caractéres ou menos!');
        }

        if(!$data['email']) {
            $errors['email'] = _('O email é obrigatório!');
        } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = _('O email é inválido!');
        } elseif(strlen($data['email']) > 100) {
            $errors['email'] = _('O email precisa ter 100 caractéres ou menos!');
        } else {
            $email = $data['id'] 
                ? (new User())
                    ->find('email = :email AND id != :id', "email={$data['email']}&id={$data['id']}")
                    ->count()
                : (new User())
                    ->find('email = :email', "email={$data['email']}")
                    ->count();

            if($email) {
                $errors['email'] = _('O email informado já está em uso! Tente outro.');
            }
        }

        if(!$data['slug']) {
            $errors['slug'] = _('O apelido é obrigatório!');
        } elseif(strlen($data['slug']) > 100) {
            $errors['slug'] = _('O apelido precisa ter 100 caractéres ou menos!');
        } else {
            $slug = $data['id'] 
                ? (new User())
                    ->find('slug = :slug AND id != :id', "slug={$data['slug']}&id={$data['id']}")
                    ->count()
                : (new User())
                    ->find('slug = :slug', "slug={$data['slug']}")
                    ->count();
            
            if($slug) {
                $errors['slug'] = _('O apelido informado já está em uso! Tente outro.');
            }
        }

        if($data['update_password']) {
            if(!$data['password']) {
                $errors['password'] = _('A senha é obrigatória!');
            } elseif(strlen($data['password']) < 5) {
                $errors['password'] = _('A senha deve conter 5 caracteres ou mais!');
            } elseif($data['password'] && $data['confirm_password'] && $data['confirm_password'] !== $data['password']) {
                $errors['password'] = _('As senhas não correspondem!');
                $errors['confirm_password'] = _('As senhas não correspondem!');
            }

            if(!$data['confirm_password']) {
                $errors['confirm_password'] = _('A confirmação de senha é obrigatória!');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}