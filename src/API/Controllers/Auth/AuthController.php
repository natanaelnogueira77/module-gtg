<?php

namespace Src\API\Controllers\Auth;

use GTG\MVC\Controller;
use Src\Models\LoginForm;

class AuthController extends Controller 
{
    public function check(array $data): void 
    {
        $loginForm = (new LoginForm())->loadData([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if($user = $loginForm->login()) {
            $this->session->setAuth($user);
            $this->APIResponse([], 200);
        } else {
            $this->setMessage('error', _('Usuário e/ou senha inválidos!'))->APIResponse([], 422);
        }
    }

    public function expired(array $data): void 
    {
        if(!$this->session->getAuth()) {
            $this->APIResponse(['success' => true], 200);
            return;
        }

        $this->APIResponse(['success' => false], 200);
    }
}