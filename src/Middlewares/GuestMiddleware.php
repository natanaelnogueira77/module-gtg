<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class GuestMiddleware extends Middleware 
{
    public function handle(Request $request): bool
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