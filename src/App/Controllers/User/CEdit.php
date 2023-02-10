<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\User\Template;
use Src\Components\Auth;
use Src\Models\User;

class CEdit extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $lang = getLang()->setFilepath('controllers/user/edit')->getContent()->setBase('index');
        $this->loadView('user/edit', [
            'user' => Auth::get()
        ]);
    }

    public function update(array $data): void 
    {
        $callback = [];
        
        try {
            $lang = getLang()->setFilepath('controllers/user/edit')->getContent()->setBase('update');
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
            ]);
            $dbUser->save();

            Auth::set($dbUser);
            $this->setMessage($lang->get('success'));
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}