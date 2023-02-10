<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;

class CError extends Controller 
{
    public function index(array $data): void 
    {
        $lang = getLang()->setFilepath('controller/error')->getContent()->setBase('index');

        $this->loadView('error/index', [
            'code' => $data['code'],
            'message' => $lang->get('error_message'),
            'exception' => $exception
        ]);
    }
}