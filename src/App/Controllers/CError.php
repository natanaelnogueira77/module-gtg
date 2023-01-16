<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;

class CError extends Controller 
{
    public function index(array $data): void 
    {
        $this->loadView('error/index', [
            'code' => $data['code'],
            'message' => 'Lamentamos, mas ocorreu um erro inesperado. Clique abaixo para voltar.',
            'exception' => $exception
        ]);
    }
}