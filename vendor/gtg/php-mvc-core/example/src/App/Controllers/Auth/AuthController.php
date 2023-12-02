<?php

namespace GTG\MVC\Example\Src\App\Controllers\Auth;

use GTG\MVC\Controller;
use GTG\MVC\Example\Src\Models\LoginForm;
use GTG\MVC\Example\Src\Models\User;

class AuthController extends Controller 
{
    public function index(array $data): void 
    {
        $loginForm = new LoginForm();
        if($this->request->isPost()) {
            $loginForm->loadData([
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            if($user = $loginForm->login()) {
                $this->session->setAuth($user);
                $this->session->setFlash('success', sprintf('Welcome, %s!', $user->name));
                if($user->isAdmin()) {
                    $this->redirect('admin.index');
                } else {
                    $this->redirect('user.index');
                }
            } else {
                $this->session->setFlash('error', 'Invalid username/password!');
            }
        }

        $this->render('auth/login', [
            'loginForm' => $loginForm
        ]);
    }

    public function logout(array $data): void 
    {
        $this->session->removeAuth();
        $this->redirect('auth.index');
    }
}