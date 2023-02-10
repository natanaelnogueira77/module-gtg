<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;
use Src\Components\Lang;

class CLanguage extends Controller 
{
    public function index(array $data): void 
    {
        Lang::setLanguage($data['lang']);

        $previous = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if($previous) {
            header("Location: {$previous}");
            exit;
        } else {
            $this->redirect('home.index');
        }
    }
}