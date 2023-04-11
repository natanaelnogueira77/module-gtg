<?php

namespace Src\App\Controllers\Web;

use Src\App\Controllers\Web\Template;

class CHome extends Template 
{
    public function index(array $data): void 
    {
        $this->addData();
        $this->loadView('web/home');
    }
}