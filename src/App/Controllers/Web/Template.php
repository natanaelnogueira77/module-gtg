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
                'desc' => _('Início')
            ],
            [
                'type' => 'item', 
                'level' => 1,
                'url' => $this->getRoute('contact.index'), 
                'desc' => _('Contato')
            ]
        ];

        if($user) {
            $rightItems = [
                [
                    'url' => $this->getRoute('user.index'), 
                    'desc' => _('Painel Principal')
                ],
                [
                    'url' => $this->getRoute('login.logout'), 
                    'desc' => _('Sair')
                ]
            ];
        } else {
            $rightItems = [
                [
                    'url' => $this->getRoute('login.index'), 
                    'desc' => _('Entrar')
                ]
            ];
        }

        $this->view->addData([
            'user' => $user,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'loadingText' => _('Aguarde, carregando...'),
            'preloader' => ['logo' => $logo],
            'header' => [
                'menu' => $headerMenu,
                'right' => [
                    'items' => $rightItems,
                    'languages' => [
                        'heading' => _('Linguagens'),
                        'curr_img' => url('resources/imgs/flags/' . '' . '.png'),
                        'items' => [
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'pt']),
                                'desc' => _('Português')
                            ],
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'en']),
                                'desc' => _('Inglês')
                            ],
                            [
                                'url' => $this->getRoute('language.index', ['lang' => 'es']),
                                'desc' => _('Espanhol')
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