<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\User\Template;
use Src\Components\Auth;
use Src\Exceptions\AppException;
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
            $data['password'] = $data['update_password'] ? $data['password'] : $user->password;

            $dbUser = new User();
            $dbUser->setValues([
                'id' => $user->id,
                'utip_id' => $user->utip_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
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
}