<?php 

namespace Src\Middlewares;

use GTG\MVC\Request;
use Src\Middlewares\Middleware;

class APIAdminMiddleware extends Middleware 
{
    public function handle(Request $request): bool
    {
        $user = $this->session->getAuth();
        if(!$user || !$user->isAdmin()) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('You must be authenticated as administrator in order to access this area!')]
            ]);
            return false;
        }

        return true;
    }
}