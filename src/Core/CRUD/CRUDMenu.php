<?php

namespace Src\Core\CRUD;

use Exception;
use Src\Core\Models\Menu;

class CRUDMenu 
{
    private $error;

    public function get(int $menuId): ?Menu 
    {
        $dbMenu = Menu::getById($menuId);
        if(!$dbMenu) {
            throw new Exception('O Menu não foi encontrado!');
        }

        return $dbMenu;
    }

    public function create(array $data = []): ?Menu 
    {   
        $dbMenu = new Menu();
        $dbMenu->setValues([
            'utip_id' => $data['utip_id'],
            'name' => $data['name'],
            'meta' => $data['meta'],
            'content' => [
                'items' => json_decode($data['content'], true)
            ]
        ]);
        $dbMenu->save();

        return $dbMenu;
    }

    public function update(int $menuId, array $data = []): ?Menu 
    {
        $dbMenu = Menu::getById($menuId);
        if(!$dbMenu) {
            throw new Exception('O Menu não foi encontrado!');
        }

        $dbMenu->setValues([
            'utip_id' => $data['utip_id'],
            'name' => $data['name'],
            'meta' => $data['meta'],
            'content' => ['items' => json_decode($data['content'], true)]
        ]);
        $dbMenu->save();

        return $dbMenu;
    }

    public function delete(int $menuId): ?Menu 
    {
        $dbMenu = Menu::getById($menuId);
        if(!$dbMenu) {
            throw new Exception('Nenhum Menu foi encontrado!');
        }

        $dbMenu->destroy();
        return $dbMenu;
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}