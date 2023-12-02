<?php 

namespace GTG\MVC\Example\Src\App\Middlewares;

use GTG\MVC\Middleware;

class GuestMiddleware extends Middleware 
{
    public function handle($router): bool
    {
        if($user = $this->session->getAuth()) {
            if($user->isAdmin()) {
                $this->redirect('admin.index');
            } else {
                $this->redirect('user.index');
            }
            return false;
        }

        return true;
    }
}