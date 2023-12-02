<?php 

namespace GTG\MVC\Example\Src\App\Middlewares;

use GTG\MVC\Middleware;

class AdminMiddleware extends Middleware 
{
    public function handle($router): bool
    {
        $user = $this->session->getAuth();
        if(!$user || !$user->isAdmin()) {
            $this->session->setFlash('error', 'You must be authenticated as an admin to have access!');
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}