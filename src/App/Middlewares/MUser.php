<?php 

namespace Src\App\Middlewares;

use CoffeeCode\Router\Router;
use Src\Components\Auth;

class MUser 
{
    public function handle(Router $router): bool
    {
        if(!Auth::get()) {
            addErrorMsg(_('Você precisa estar autenticado para acessar essa área!'));
            redirect($router->route('login.index'));
            return false;
        }

        return true;
    }
}