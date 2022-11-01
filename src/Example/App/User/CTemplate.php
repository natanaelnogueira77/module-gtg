<?php

namespace Src\Example\App\User;

use League\Plates\Engine;
use Src\Core\App\CTheme;

class CTemplate extends CTheme 
{
    protected $view;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->setTemplate(__DIR__ . '/../../Views/user');
        $this->setStylesPath('src/Example/Views/user/assets/css');
        $this->setScriptsPath('src/Example/Views/user/assets/js');
    }

    public function addMainData(): void 
    {
        $user = $this->getSessionUser();
        $type = $user->utip_id ?? 0;
        $exception = null;

        if(!$user) {
            addErrorMsg('Você precisa estar logado para acessar esta área!');
            header('Location: ' . url('entrar'));
            exit();
        }

        $config = $this->getConfigMetas(['logo', 'logo_icon', 'style']);
        $menus = $this->getMenus($type, ['left-architect', 'header-architect'], [
            'user_slug' => $user->slug
        ]);
        $userType = $this->getUserTypeById($type);

        if($userType) {
            $userTypeName = $userType->name_sing;
        }

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

        $rightItems = [
            ['url' => url('u'), 'desc' => 'Painel Principal'],
            ['url' => url('u/editar-conta'), 'desc' => 'Editar Meus Dados'],
            ['url' => url(), 'desc' => 'Voltar Para o Início'],
            ['divider' => true],
            ['url' => url('logout'), 'desc' => 'Sair']
        ];

        $logo = $config['logo'];
        $logoIcon = $config['logo_icon'];

        $leftColor = $config['style'] ? $bgColors['left'][$config['style']] : null;
        $leftMenu = $menus['left-architect']->content['items'];
        $noLeft = $leftMenu ? false : true;

        $headerColor = $config['style'] ? $bgColors['header'][$config['style']] : null;
        $headerMenu = $menus['header-architect']->content['items'];
        $headerLeft = $leftMenu ? true : false;

        $footer = [];

        $this->view->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'noLeft' => $noLeft,
            'left' => [
                'color' => $leftColor,
                'menu' => $leftMenu,
                'active' => ROOT . filter_input(INPUT_GET, 'route', FILTER_DEFAULT)
            ],
            'header' => [
                'left' => $headerLeft,
                'color' => $headerColor,
                'menu' => $headerMenu,
                'right' => [
                    'show' => true,
                    'items' => $rightItems,
                    'avatar' => ('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email)))),
                    'avatar_title' => $user->name,
                    'avatar_subtitle' => $userTypeName
                ]
            ],
            'footer' => $footer
        ]);

        $this->template->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon
        ]);
    }
}