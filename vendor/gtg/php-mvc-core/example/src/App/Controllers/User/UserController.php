<?php

namespace GTG\MVC\Example\Src\App\Controllers\User;

use GTG\MVC\Example\Src\App\Controllers\User\TemplateController;

class UserController extends TemplateController 
{
    public function index(array $data): void 
    {
        $this->addData();
        $this->render('user/index', [
            'data' => $data
        ]);
    }
}