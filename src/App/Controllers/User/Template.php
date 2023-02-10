<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\Lang;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class Template extends Controller 
{
    public function addData(): void 
    {
        $config = Config::getMetasByName(['logo', 'logo_icon', 'style']);

        $lang = getLang()->setFilepath('controllers/user/template')->getContent();

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
            [
                'type' => 'heading', 
                'desc' => $lang->get('left.menu.heading1')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-display2', 
                'url' => $this->getRoute('user.index'), 
                'desc' => $lang->get('left.menu.item1')
            ]
        ];

        $headerMenu = [
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-home', 
                'url' => $this->getRoute('login.index'), 
                'desc' => $lang->get('header.menu.item1')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-phone', 
                'url' => $this->getRoute('contact.index'), 
                'desc' => $lang->get('header.menu.item2')
            ]
        ];

        $user = Auth::get();
        $user->userType();

        $this->view->addData([
            'user' => $user,
            'storeAt' => 'storage/users/user' . $user->id,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'loadingText' => $lang->get('loading_text'),
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
                    'languages' => [
                        'heading' => $lang->get('header.right.languages.heading'),
                        'curr_img' => url('resources/imgs/flags/' . Lang::getLanguage() . '.png'),
                        'items' => [
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'pt']),
                                'desc' => 'Português'
                            ],
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'en']),
                                'desc' => 'English'
                            ],
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'es']),
                                'desc' => 'Español'
                            ]
                        ]
                    ],
                    'items' => [
                        [
                            'url' => $this->getRoute('user.index'), 
                            'desc' => $lang->get('header.right.items.item1')
                        ],
                        [
                            'url' => $this->getRoute('user.edit.index'), 
                            'desc' => $lang->get('header.right.items.item2')
                        ],
                        [
                            'url' => $this->getRoute('login.index'), 
                            'desc' => $lang->get('header.right.items.item3')
                        ],
                        ['divider' => true],
                        [
                            'url' => $this->getRoute('login.logout'), 
                            'desc' => $lang->get('header.right.items.item4')
                        ]
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