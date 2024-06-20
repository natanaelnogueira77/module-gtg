<?php 

namespace Middlewares;

use GTG\MVC\Request;

class GuestMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if($user = $this->session->getAuth()) {
            $this->redirect($user->isAdmin() ? 'admin.index' : 'user.index');
            return false;
        }

        return true;
    }
}