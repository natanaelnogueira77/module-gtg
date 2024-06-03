<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class APIUserMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!$this->session->getAuth()) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('Você precisa estar autenticado para acessar essa área!')]
            ]);
            return false;
        }

        return true;
    }
}