<?php

namespace GTG\MVC\Example\Src\App\Controllers\Web;

use GTG\MVC\Controller;

class TemplateController extends Controller 
{
    public function addData(): void 
    {
        $user = $this->session->getAuth() ? $this->session->getAuth() : null;
        $this->addViewData([
            'theme' => 'Theme data here'
        ]);
    }
}