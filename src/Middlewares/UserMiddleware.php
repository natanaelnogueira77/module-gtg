<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;
use Src\Middlewares\Middleware;

class UserMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!$this->session->getAuth()) {
            $this->setErrorFlash(_('You must be authenticated in order to access this area!'));
            $this->redirect('auth.index');
            return false;
        }

        return true;
    }
}