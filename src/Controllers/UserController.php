<?php 

namespace Src\Controllers;

use GTG\MVC\Request;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('user', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Painel Principal | %s'), $this->appData['app_name'])
            ),
            'cards' => []
        ]);
    }
}