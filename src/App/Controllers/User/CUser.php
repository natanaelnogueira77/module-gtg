<?php

namespace Src\App\Controllers\User;

use Src\App\Controllers\User\Template;

class CUser extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $lang = getLang()->setFilepath('controller/user/user')->getContent()->setBase('index');
        $blocks = [];

        $this->loadView('user/index', [
            'blocks' => $blocks
        ]);
    }
}