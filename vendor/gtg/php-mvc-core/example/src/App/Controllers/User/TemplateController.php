<?php

namespace GTG\MVC\Example\Src\App\Controllers\User;

use GTG\MVC\Controller;

class TemplateController extends Controller 
{
    public function addData(): void 
    {
        $user = $this->session->getAuth();
        $this->addViewData([
            'theme' => 'Theme data here'
        ]);
    }
}