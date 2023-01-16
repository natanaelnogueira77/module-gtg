<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class Template extends Controller 
{
    public function addData(): void 
    {
        $config = Config::getMetasByName(['logo', 'logo_icon', 'style']);

        $logo = url($config['logo']);
        $logoIcon = url($config['logo_icon']);
        $style = $config['style'];

        $bgColors = [
            'left' => [
                'light' => 'bg-heavy-rain sidebar-text-dark',
                'dark' => 'bg-slick-carbon sidebar-text-light'
            ],
            'header' => [
                'light' => 'bg-heavy-rain header-text-dark',
                'dark' => 'bg-slick-carbon header-text-light'
            ]
        ];

        $leftMenu = [
            ['type' => 'heading', 'desc' => 'Paineis'],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-display2', 
                'url' => $this->getRoute('user.index'), 
                'desc' => 'Painel Principal'
            ]
        ];

        $headerMenu = [
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-home', 
                'url' => $this->getRoute('login.index'), 
                'desc' => 'Início'
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-phone', 
                'url' => $this->getRoute('contact.index'), 
                'desc' => 'Contato'
            ]
        ];

        $user = Auth::get();
        $user->userType();

        $this->view->addData([
            'user' => $user,
            'storeAt' => 'storage/users/user' . $user->id,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'noLeft' => false,
            'noFooter' => false,
            'left' => [
                'color' => $config['style'] ? $bgColors['left'][$config['style']] : null,
                'menu' => $leftMenu,
                'active' => ROOT . filter_input(INPUT_GET, 'route', FILTER_DEFAULT)
            ],
            'header' => [
                'left' => true,
                'color' => $config['style'] ? $bgColors['header'][$config['style']] : null,
                'menu' => $headerMenu,
                'right' => [
                    'show' => true,
                    'items' => [
                        ['url' => $this->getRoute('user.index'), 'desc' => 'Painel Principal'],
                        ['url' => $this->getRoute('user.edit.index'), 'desc' => 'Editar Meus Dados'],
                        ['url' => $this->getRoute('login.index'), 'desc' => 'Voltar ao Início'],
                        ['divider' => true],
                        ['url' => $this->getRoute('login.logout'), 'desc' => 'Sair']
                    ],
                    'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))),
                    'avatar_title' => $user->name,
                    'avatar_subtitle' => $user->userType->name_sing
                ]
            ],
            'footer' => ['rightText' => 'Dashboard v' . GTG_VERSION]
        ]);
    }
}