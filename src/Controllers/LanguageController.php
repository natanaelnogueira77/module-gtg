<?php

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Controllers\Controller;

class LanguageController extends Controller 
{
    public function index(Request $request): void 
    {
        if($request->get('lang') == 'pt') {
            $this->session->setLanguage(['pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil']);
        } elseif($request->get('lang') == 'es') {
            $this->session->setLanguage(['es_ES.utf-8', 'es_ES', 'Spanish_Spain']);
        } elseif($request->get('lang') == 'en') {
            $this->session->setLanguage(['en_US.utf-8', 'en_US', 'English_US']);
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