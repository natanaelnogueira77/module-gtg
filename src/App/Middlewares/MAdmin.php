<?php 

namespace Src\App\Middlewares;

use CoffeeCode\Router\Router;
use Src\Components\Auth;

class MAdmin 
{
    public function handle(Router $router): bool
    {
        $lang = getLang()->setFilepath('middlewares/admin')->getContent()->setBase('handle');
        $user = Auth::get();
        if(!$user || !$user->isAdmin()) {
            addErrorMsg($lang->get('not_authenticated'));
            redirect($router->route('login.index'));
            return false;
        }

        return true;
    }
}