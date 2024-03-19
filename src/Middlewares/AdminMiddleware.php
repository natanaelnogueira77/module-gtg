<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class AdminMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        $user = $this->session->getAuth();
        if(!$user || !$user->isAdmin()) {
            $this->setErrorFlash(
                _('You must be authenticated as administrator in order to access this area!')
            );
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}