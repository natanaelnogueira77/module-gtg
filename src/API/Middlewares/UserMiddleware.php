<?php 

namespace Src\API\Middlewares;

use GTG\MVC\Middleware;

class UserMiddleware extends Middleware 
{
    public function handle($router): bool
    {
        if(!$this->session->getAuth()) {
            return false;
        }

        return true;
    }
}