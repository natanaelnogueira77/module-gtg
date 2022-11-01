<?php

namespace Src\Example\App\Web;

use League\Plates\Engine;
use Src\Core\App\CTheme;
use Src\Core\Models\Config;

class CTemplate extends CTheme 
{
    public function __construct($router) 
    {
        parent::__construct($router);
        $this->setTheme('courses-master');
        $this->setTemplate(__DIR__ . '/../../Views/web');
        $this->setStylesPath('src/Example/Views/web/assets/css');
        $this->setScriptsPath('src/Example/Views/web/assets/js');
    }

    public function addMainData(): void 
    {
        $user = $this->getSessionUser();
        $type = $user->utip_id ? $user->utip_id : 0;

        Config::addMetas(['login_img']);

        $config = $this->getConfigMetas(['logo', 'logo_icon']);
        $menus = $this->getMenus($type, ['header-architect'], [
            'user_slug' => $user->slug
        ]);

        $logo = $config['logo'];
        $logoIcon = $config['logo_icon'];
        $socials = [];

        $headerMenu = $menus['header-architect']->content['items'];

        if($user) {
            $rightItems = [
                ['url' => url('u'), 'desc' => 'Painel Principal'],
                ['url' => url('logout'), 'desc' => 'Sair']
            ];
        } else {
            $rightItems = [
                ['url' => url('entrar'), 'desc' => 'Entrar']
            ];
        }

        $this->view->addData([
            'user' => $user,
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
                'socials' => $socials,
                'items' => $headerMenu
            ]
        ]);

        $this->template->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon
        ]);
    }
}