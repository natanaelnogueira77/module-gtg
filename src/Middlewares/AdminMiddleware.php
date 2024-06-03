<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class AdminMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!($user = $this->session->getAuth()) || !$user->isAdmin()) {
            $this->setErrorFlash(_('Você precisa estar autenticado como administrador para acessar essa área!'));
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}