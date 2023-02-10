<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Web\Template;
use Src\Models\User;

class CHome extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $lang = getLang()->setFilepath('controllers/web/home')->getContent()->setBase('index');

        $this->loadView('web/home');
    }
}