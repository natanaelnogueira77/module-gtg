<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;

class CLanguage extends Controller 
{
    public function index(array $data): void 
    {
        if($data['lang'] == 'pt') {
            setLanguage(['pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil']);
        } elseif($data['lang'] == 'es') {
            setLanguage(['es_ES.utf-8', 'es_ES', 'Spanish_Spain']);
        } elseif($data['lang'] == 'en') {
            setLanguage(['en_US.utf-8', 'en_US', 'English_US']);
        }

        $previous = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if($previous) {
            header("Location: {$previous}");
            exit;
        } else {
            $this->redirect('home.index');
        }
    }
}