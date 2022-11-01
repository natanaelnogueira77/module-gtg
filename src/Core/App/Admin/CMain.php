<?php

namespace Src\Core\App\Admin;

use Src\Core\App\Admin\CTemplate;
use Src\Core\Models\Config;
use Src\Core\Models\User;
use Src\Core\Models\UserType;

class CMain extends CTemplate 
{
    public function main(): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $configData = Config::getMetaValues();

        $urls = [
            'table_users' => $this->getRoute('CCAAUsers.list'),
            'email_config' => [
                'url' => $this->getRoute('CCAAConfig.smtp'),
                'method' => 'PUT'
            ],
            'usertypes_config' => [
                'url' => $this->getRoute('CCAAConfig.usertypes'),
                'method' => 'PUT'
            ],
            'system_config' => [
                'url' => $this->getRoute('CCAAConfig.system'),
                'method' => 'PUT'
            ],
            'db_config' => [
                'url' => $this->getRoute('CCAAConfig.database'),
                'method' => 'PUT'
            ]
        ];

        $userTypes = UserType::get();
        if($userTypes) {
            $userTypes = UserType::getGroupedBy($userTypes, 'id');
        }

        $this->loadView('page', [
            'title' => 'Administrador | ' . SITE,
            'page' => [
                'title' => 'Painel do Administrador',
                'subtitle' => 'Relatórios e gerenciamento do sistema',
                'icon' => 'pe-7s-home',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('main', [
                'configData' => $configData,
                'userTypes' => $userTypes,
                'emailData' => MAIL,
                'mainData' => MAIN,
                'dbData' => DATA_LAYER,
                'gtgVersion' => GTG_VERSION,
                'countUsers' => User::countUsers(),
                'urls' => $urls
            ]),
            'scripts' => [$this->getScript('main')],
            'exception' => $exception
        ]);
    }
}