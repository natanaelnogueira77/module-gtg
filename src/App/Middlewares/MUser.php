<?php 

namespace Src\App\Middlewares;

use CoffeeCode\Router\Router;
use Src\Components\Auth;

class MUser 
{
    public function handle(Router $router): bool
    {
        $lang = getLang()->setFilepath('middlewares/user')->getContent()->setBase('handle');
        if(!Auth::get()) {
            addErrorMsg($lang->get('not_authenticated'));
            redirect($router->route('login.index'));
            return false;
        }

        return true;
    }
}