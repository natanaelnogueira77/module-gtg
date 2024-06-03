<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;

class APIAdminMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        if(!($user = $this->session->getAuth()) || !$user->isAdmin()) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('Você precisa estar autenticado como administrador para acessar essa área!')]
            ]);
            return false;
        }

        return true;
    }
}