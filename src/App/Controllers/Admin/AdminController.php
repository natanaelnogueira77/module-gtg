<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\TemplateController;
use Src\Components\FileSystem;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;
use Src\Utils\ErrorMessages;

class AdminController extends TemplateController  
{
    public function index(array $data): void 
    {
        $this->addData();

        if($dbUserCounts = (new User())->get([], 'utip_id, COUNT(*) as users_count')->group('utip_id')->fetch('count')) {
            foreach($dbUserCounts as $dbUserCount) {
                $usersCount[$dbUserCount->utip_id] = $dbUserCount->users_count;
            }
        }

        $configMetas = (new Config())->getGroupedMetas([
            Config::KEY_STYLE,
            Config::KEY_LOGO,
            Config::KEY_LOGO_ICON,
            Config::KEY_LOGIN_IMG
        ]);

        $this->render('admin/index', [
            'configData' => [
                'style' => $configMetas['style'],
                'logo' => [
                    'uri' => $configMetas['logo'],
                    'url' => FileSystem::getLink($configMetas['logo'])
                ],
                'logo_icon' => [
                    'uri' => $configMetas['logo_icon'],
                    'url' => FileSystem::getLink($configMetas['logo_icon'])
                ],
                'login_img' => [
                    'uri' => $configMetas['login_img'],
                    'url' => FileSystem::getLink($configMetas['login_img'])
                ]
            ],
            'userTypes' => (new UserType())->get()->fetch(true),
            'usersCount' => $usersCount
        ]);
    }

    public function system(array $data): void 
    {
        if(!$objects = (new Config())->saveManyMetas([
            Config::KEY_STYLE => $data['style'],
            Config::KEY_LOGO => $data['logo'],
            Config::KEY_LOGO_ICON => $data['logo_icon'],
            Config::KEY_LOGIN_IMG => $data['login_img']
        ])) {
            $this->setMessage('error', ErrorMessages::requisition())->APIResponse([], 500);
            return;
        }

        if($errors = Config::getErrorsFromMany($objects, true)) {
            $this->setMessage('error', ErrorMessages::form())->setErrors($errors)->APIResponse([], 422);
            return;
        }
        
        $this->session->setFlash('success', _('Configurações atualizadas com sucesso!'));
        $this->APIResponse([], 200);
    }
}