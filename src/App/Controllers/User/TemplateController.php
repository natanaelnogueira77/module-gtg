<?php

namespace Src\App\Controllers\User;

use GTG\MVC\Controller;
use Src\Components\FileSystem;
use Src\Components\Theme;
use Src\Data\ColorsData;
use Src\Data\MenuData;
use Src\Models\Config;

class TemplateController extends Controller 
{
    public function addData(): void 
    {
        $configMetas = (new Config())->getGroupedMetas([
            Config::KEY_LOGO, 
            Config::KEY_LOGO_ICON, 
            Config::KEY_STYLE
        ]);

        $user = $this->session->getAuth();
        $user->userType();

        $this->view->addData([
            'theme' => (new Theme())->loadData([
                'logo' => FileSystem::getLink($configMetas[Config::KEY_LOGO]),
                'logo_icon' => FileSystem::getLink($configMetas[Config::KEY_LOGO_ICON]),
                'loading_text' => _('Aguarde, carregando...'),
                'has_header' => true,
                'has_left' => true,
                'has_footer' => false,
                'header' => [
                    'left' => true,
                    'color' => ColorsData::header($configMetas[Config::KEY_STYLE]),
                    'menu' => MenuData::getHeaderMenuItems($this->router, $user),
                    'right' => [
                        'show' => true,
                        'items' => MenuData::getRightMenuItems($this->router, $user),
                        'languages' => [
                            'heading' => _('Linguagens'),
                            'curr_img' => url("public/imgs/flags/{$this->session->getLanguage()[1]}.png"),
                            'items' => [
                                ['url' => $this->getRoute('language.index', ['lang' => 'pt']), 'desc' => _('Português')],
                                ['url' => $this->getRoute('language.index', ['lang' => 'en']), 'desc' => _('Inglês')],
                                ['url' => $this->getRoute('language.index', ['lang' => 'es']), 'desc' => _('Espanhol')]
                            ]
                        ],
                        'items' => MenuData::getRightMenuItems($this->router, $user),
                        'avatar' => $user->getAvatarURL(),
                        'avatar_title' => $user->name,
                        'avatar_subtitle' => $user->userType->name_sing
                    ]
                ],
                'left' => [
                    'color' => ColorsData::left($configMetas[Config::KEY_STYLE]),
                    'menu' => MenuData::getLeftMenuItems($this->router, $user),
                    'active' => url() . filter_input(INPUT_GET, 'route', FILTER_DEFAULT)
                ]
            ])
        ]);
    }
}