<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class APIUserMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!$this->session->getAuth()) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('You must be authenticated in order to access this area!')]
            ]);
            return false;
        }

        return true;
    }
}