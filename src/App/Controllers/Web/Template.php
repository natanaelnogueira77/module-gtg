<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Models\Config;
use Src\Models\User;

class Template extends Controller 
{
    public function addData(): void 
    {
        $user = Auth::get();
        $config = Config::getMetasByName(['logo', 'logo_icon']);

        $logo = url($config['logo']);
        $logoIcon = url($config['logo_icon']);

        $headerMenu = [
            [
                'type' => 'item', 
                'level' => 1, 
                'url' => $this->getRoute('login.index'), 
                'desc' => 'Início'
            ],
            [
                'type' => 'item', 
                'level' => 1,
                'url' => $this->getRoute('contact.index'), 
                'desc' => 'Contato'
            ]
        ];

        if($user) {
            $rightItems = [
                ['url' => $this->getRoute('user.index'), 'desc' => 'Painel Principal'],
                ['url' => $this->getRoute('login.logout'), 'desc' => 'Sair']
            ];
        } else {
            $rightItems = [
                ['url' => $this->getRoute('login.index'), 'desc' => 'Entrar']
            ];
        }

        $this->view->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'preloader' => ['logo' => $logo],
            'header' => [
                'menu' => $headerMenu,
                'right' => [
                    'items' => $rightItems
                ]
            ],
            'footer' => [
                'logo' => $logo,
                'socials' => [],
                'items' => $headerMenu
            ]
        ]);
    }
}