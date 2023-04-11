<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\Template;
use Src\Exceptions\AppException;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class CAdmin extends Template  
{
    public function index(array $data): void 
    {
        $this->addData();

        $dbUserCounts = (new User())->get([], 'utip_id, COUNT(*) as users_count')->group('utip_id')->fetch('count');
        if($dbUserCounts) {
            foreach($dbUserCounts as $dbUserCount) {
                $usersCount[$dbUserCount->utip_id] = $dbUserCount->users_count;
            }
        }

        $this->loadView('admin/index', [
            'configMetas' => (new Config())->getGroupedMetas(['login_img', 'logo', 'logo_icon', 'style']),
            'userTypes' => (new UserType())->get()->fetch(true),
            'usersCount' => $usersCount
        ]);
    }

    public function system(array $data): void 
    {
        $callback = [];
        
        try {
            (new Config())->saveManyMetas([
                'style' => $data['style'],
                'logo' => $data['logo'],
                'logo_icon' => $data['logo_icon'],
                'login_img' => $data['login_img']
            ]);
            
            $this->setMessage(_('Configurações atualizadas com sucesso!'));
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }
}