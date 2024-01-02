<?php 

namespace Src\API\Middlewares;

use GTG\MVC\Middleware;

class AdminMiddleware extends Middleware 
{
    public function handle($router): bool
    {
        $user = $this->session->getAuth();
        if(!$user || !$user->isAdmin()) {
            return false;
        }

        return true;
    }
}