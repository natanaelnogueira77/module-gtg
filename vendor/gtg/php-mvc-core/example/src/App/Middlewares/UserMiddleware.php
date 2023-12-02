<?php 

namespace GTG\MVC\Example\Src\App\Middlewares;

use GTG\MVC\Middleware;

class UserMiddleware extends Middleware 
{
    public function handle($router): bool
    {
        if(!$this->session->getAuth()) {
            $this->session->setFlash('error', 'You must be logged in to have access!');
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}