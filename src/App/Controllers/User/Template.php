<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Models\Config;

class Template extends Controller 
{
    public function addData(): void 
    {
        $configMetas = (new Config())->getGroupedMetas(['logo', 'logo_icon', 'style']);

        $logo = $configMetas && $configMetas['logo'] ? url($configMetas['logo']) : '';
        $logoIcon = $configMetas && $configMetas['logo_icon'] ? url($configMetas['logo_icon']) : '';
        $style = $configMetas['style'];

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
                'desc' => _('Painéis')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-display2', 
                'url' => $this->getRoute('user.index'), 
                'desc' => _('Painel Principal')
            ]
        ];

        $headerMenu = [
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-home', 
                'url' => $this->getRoute('login.index'), 
                'desc' => _('Início')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'nav-link-icon fa fa-phone', 
                'url' => $this->getRoute('contact.index'), 
                'desc' => _('Contato')
            ]
        ];

        $user = Auth::get();
        $user->userType();

        $this->view->addData([
            'user' => $user,
            'storeAt' => 'storage/users/user' . $user->id,
            'logo' => $logo,
            'shortcutIcon' => $logoIcon,
            'loadingText' => _('Aguarde, carregando...'),
            'noLeft' => false,
            'noFooter' => false,
            'left' => [
                'color' => $configMetas['style'] ? $bgColors['left'][$configMetas['style']] : null,
                'menu' => $leftMenu,
                'active' => ROOT . filter_input(INPUT_GET, 'route', FILTER_DEFAULT)
            ],
            'header' => [
                'left' => true,
                'color' => $configMetas['style'] ? $bgColors['header'][$configMetas['style']] : null,
                'menu' => $headerMenu,
                'right' => [
                    'show' => true,
                    'languages' => [
                        'heading' => _('Linguagens'),
                        'curr_img' => url('resources/imgs/flags/' . LANG[1] . '.png'),
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
                    ],
                    'items' => [
                        [
                            'url' => $this->getRoute('user.index'), 
                            'desc' => _('Painel Principal')
                        ],
                        [
                            'url' => $this->getRoute('user.edit.index'), 
                            'desc' => _('Editar meus Dados')
                        ],
                        [
                            'url' => $this->getRoute('login.index'), 
                            'desc' => _('Voltar ao Início')
                        ],
                        ['divider' => true],
                        [
                            'url' => $this->getRoute('login.logout'), 
                            'desc' => _('Sair')
                        ]
                    ],
                    'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))),
                    'avatar_title' => $user->name,
                    'avatar_subtitle' => $user->userType->name_sing
                ]
            ],
            'footer' => ['rightText' => sprintf(_('Painel %s'), 'v' . GTG_VERSION)]
        ]);
    }
}