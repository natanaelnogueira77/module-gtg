<?php 

namespace Src\App\Middlewares;

use CoffeeCode\Router\Router;
use Src\Components\Auth;

class MGuest 
{
    public function handle(Router $router): bool
    {
        $lang = getLang()->setFilepath('middlewares/guest')->getContent()->setBase('handle');
        $user = Auth::get();
        if($user) {
            if($user->isAdmin()) {
                redirect($router->route('admin.index'));
            } else {
                redirect($router->route('user.index'));
            }
            return false;
        }

        return true;
    }
}