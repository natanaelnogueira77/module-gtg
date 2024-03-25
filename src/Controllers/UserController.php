<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Views\LayoutFactory;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('user', [
            'layout' => LayoutFactory::createMain(
                sprintf(_('User Page | %s'), $this->appData['app_name'])
            )
        ]);
    }
}