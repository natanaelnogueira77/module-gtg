<?php 

namespace Middlewares;

use GTG\MVC\Request;

class UserMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!$this->session->getAuth()) {
            $this->setErrorFlash(_('Você precisa estar autenticado para acessar essa área!'));
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}