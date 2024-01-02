<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\TemplateController;
use Src\Components\FileSystem;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

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
}