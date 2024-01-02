<?php

namespace Src\API\Controllers\Admin;

use GTG\MVC\Controller;
use Src\Models\Config;
use Src\Utils\ErrorMessages;

class AdminController extends Controller 
{
    public function system(array $data): void 
    {
        if(!$objects = (new Config())->saveManyMetas([
            Config::KEY_STYLE => $data['style'],
            Config::KEY_LOGO => $data['logo'],
            Config::KEY_LOGO_ICON => $data['logo_icon'],
            Config::KEY_LOGIN_IMG => $data['login_img']
        ])) {
            $this->setMessage('error', ErrorMessages::requisition())->APIResponse([], 500);
            return;
        }

        if($errors = Config::getErrorsFromMany($objects, true)) {
            $this->setMessage('error', ErrorMessages::form())->setErrors($errors)->APIResponse([], 422);
            return;
        }
        
        $this->session->setFlash('success', _('Configurações atualizadas com sucesso!'));
        $this->APIResponse([], 200);
    }
}