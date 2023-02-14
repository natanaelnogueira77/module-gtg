<?php 

namespace Src\App\Middlewares;

use CoffeeCode\Router\Router;
use Src\Components\Auth;

class MAdmin 
{
    public function handle(Router $router): bool
    {
        $user = Auth::get();
        if(!$user || !$user->isAdmin()) {
            addErrorMsg(_('Você precisa estar autenticado como Administrador para acessar essa área!'));
            redirect($router->route('login.index'));
            return false;
        }

        return true;
    }
}