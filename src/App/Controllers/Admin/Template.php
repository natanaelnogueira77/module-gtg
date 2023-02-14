<?php

namespace Src\App\Controllers\Admin;

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
            [
                'type' => 'heading', 
                'desc' => _('Painéis')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-display2', 
                'url' => $this->getRoute('admin.index'), 
                'desc' => _('Painel Principal')
            ],
            [
                'type' => 'heading', 
                'desc' => _('Usuários')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-users', 
                'url' => $this->getRoute('admin.users.index'), 
                'desc' => _('Usuários')
            ],
            [
                'type' => 'item', 
                'level' => 1, 
                'icon' => 'metismenu-icon pe-7s-user', 
                'url' => $this->getRoute('admin.users.create'), 
                'desc' => _('Criar Usuário')
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
                    ],
                    'items' => [
                        [
                            'url' => $this->getRoute('admin.index'), 
                            'desc' => _('Painel Principal')
                        ],
                        [
                            'url' => $this->getRoute('user.edit.index'), 
                            'desc' => _('Editar Meus Dados')
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