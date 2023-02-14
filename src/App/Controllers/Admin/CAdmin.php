<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\Template;
use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class CAdmin extends Template  
{
    public function index(array $data): void 
    {
        $this->addData();
        $configData = Config::getMetaValues();

        $this->loadView('admin/index', [
            'configData' => $configData,
            'gtgVersion' => GTG_VERSION,
            'userTypes' => (new UserType())->get()->fetch(true),
            'countUsers' => User::countUsers()
        ]);
    }

    public function system(array $data): void 
    {
        $callback = [];
        
        try {
            Config::saveMetas([
                'style' => $data['style'],
                'logo' => $data['logo'],
                'logo_icon' => $data['logo_icon'],
                'login_img' => $data['login_img']
            ]);
            
            $this->setMessage(_('Configurações atualizadas com sucesso!'));
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }
}