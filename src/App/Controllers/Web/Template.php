<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\Lang;
use Src\Models\Config;
use Src\Models\User;

class Template extends Controller 
{
    public function addData(): void 
    {
        $user = Auth::get();
        $config = Config::getMetasByName(['logo', 'logo_icon']);

        $lang = getLang()->setFilepath('controllers/web/template')->getContent();

        $logo = url($config['logo']);
        $logoIcon = url($config['logo_icon']);

        $headerMenu = [
            [
                'type' => 'item', 
                'level' => 1, 
                'url' => $this->getRoute('login.index'), 
                'desc' => $lang->get('header.menu.item1')
            ],
            [
                'type' => 'item', 
                'level' => 1,
                'url' => $this->getRoute('contact.index'), 
                'desc' => $lang->get('header.menu.item2')
            ]
        ];

        if($user) {
            $rightItems = [
                [
                    'url' => $this->getRoute('user.index'), 
                    'desc' => $lang->get('header.right.items.item1')
                ],
                [
                    'url' => $this->getRoute('login.logout'), 
                    'desc' => $lang->get('header.right.items.item2')
                ]
            ];
        } else {
            $rightItems = [
                [
                    'url' => $this->getRoute('login.index'), 
                    'desc' => $lang->get('header.right.items.item3')
                ]
            ];
        }

        $this->view->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'loadingText' => $lang->get('loading_text'),
            'preloader' => ['logo' => $logo],
            'header' => [
                'menu' => $headerMenu,
                'right' => [
                    'items' => $rightItems,
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
                    ]
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