<?php

namespace Src\Core\App\Admin;

use Src\Core\App\Admin\CTemplate;
use Src\Core\CRUD\CRUDMenu;
use Src\Core\Models\Menu;
use Src\Core\Models\User;
use Src\Core\Models\UserType;

class CMenus extends CTemplate 
{
    public function main(): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $menus = [];

        $userTypes = UserType::get();
        $userTypes = UserType::getGroupedBy($userTypes);

        $dbMenus = Menu::get();
        if($dbMenus) {
            foreach($dbMenus as $menu) {
                if($menu->utip_id) {
                    $menu->transformParams(['user_type' => $userTypes[$menu->utip_id]->name_sing]);
                }

                $menus[$menu->id] = $menu->getValues();
                $menus[$menu->id]['edit'] = $this->getRoute('CCAAMenus.get', ['menu_id' => $menu->id]);
                $menus[$menu->id]['delete'] = [
                    'url' => $this->getRoute('CCAAMenus.delete', ['menu_id' => $menu->id]),
                    'method' => 'DELETE'
                ];
            }
        }

        $urls = [
            'creation' => $this->getRoute('CCAAMenus.creation')
        ];

        $this->loadView('page', [
            'title' => 'Menus | ' . SITE,
            'page' => [
                'title' => 'Menus do Sistema',
                'subtitle' => 'Edite os menus dos templates',
                'icon' => 'pe-7s-menu',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('menus', [
                'menus' => $menus,
                'urls' => $urls
            ]),
            'scripts' => [$this->getScript('menus')],
            'exception' => $exception
        ]);
    }

    public function get(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;
        $menuData = [];

        $userTypes = UserType::get();
        if($userTypes) {
            $userTypes = UserType::getGroupedBy($userTypes, 'id');
            $userTypes[0] = new UserType();
            $userTypes[0]->id = 0;
            $userTypes[0]->name_sing = 'Não Logado';
            $userTypes[0]->name_plur = 'Não Logados';
        }

        $urls = [
            'save' => [
                'url' => $this->getRoute('CCAAMenus.create'),
                'method' => 'POST'
            ],
            'return' => $this->getRoute('CCAAMenus.main')
        ];

        $menu = Menu::getById(intval($data['menu_id']));
        if($menu) {
            $menu->transformParams(['user_type' => (
                isset($userTypes[$menu->utip_id]) ? $userTypes[$menu->utip_id]->name_sing : '')
            ]);
            $menuData = $menu->getValues();

            $urls['save'] = [
                'url' => $this->getRoute('CCAAMenus.update', ['menu_id' => $menu->id]),
                'method' => 'PUT'
            ];
        }

        $this->loadView('page', [
            'title' => 'Salvar Menu | ' . SITE,
            'page' => [
                'title' => "Editar Menu - {$menu->name}",
                'subtitle' => 'Clique em Novo Ítem para adicionar links',
                'icon' => 'pe-7s-menu',
                'icon_color' => 'bg-malibu-beach'
            ],
            'template' => $this->getTemplateView('save-menu', $menuData + [
                'urls' => $urls,
                'icons' => $icons,
                'userTypes' => $userTypes,
                'menu' => $menu
            ]),
            'scripts' => [$this->getScript('save-menu')],
            'exception' => $exception
        ]);
    }

    public function create(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $CRUDMenu = new CRUDMenu();
            $dbMenu = $CRUDMenu->create($data);

            addSuccessMsg("O Menu \"{$dbMenu->name}\" foi criado com sucesso!");
            $callback['link'] = $this->getRoute('CCAAMenus.get', ['menu_id' => $dbMenu->id]);
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function update(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $CRUDMenu = new CRUDMenu();
            $dbMenu = $CRUDMenu->update(intval($data['menu_id']), $data);

            $this->setMessage("O Menu \"{$dbMenu->name}\" foi alterado com sucesso!");
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function delete(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $CRUDMenu = new CRUDMenu();
            $dbMenu = $CRUDMenu->delete(intval($data['menu_id']));

            $this->setMessage("O Menu \"{$dbMenu->name}\" foi excluído com sucesso.");
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}