<?php 

namespace Controllers;

use GTG\MVC\Request;
use Models\LoginForm;
use Utils\ThemeUtils;
use Views\Pages\AuthPage;

class AuthController extends Controller
{
    public function index(Request $request): void
    {
        echo new AuthPage(request: $request);
    }

    public function login(Request $request): void 
    {
        $loginForm = new LoginForm();
        $loginForm->email = $request->get('email');
        $loginForm->password = $request->get('password');

        $user = $loginForm->login();
        $this->session->setAuth($user);

        $this->setSuccessFlash(sprintf(_("Seja bem-vindo(a), %s!"), $user->name));
        $this->writeSuccessResponse([
            'redirectURL' => isset($params['redirect']) 
                ? url($params['redirect']) 
                : $this->router->route($user->isAdmin() ? 'admin.index' : 'user.index')
        ]);
    }

    public function logout(Request $request): void 
    {
        $this->session->removeAuth();
        $this->redirect('auth.index');
    }

    public function expired(Request $request): void 
    {
        $this->writeSuccessResponse(['success' => !$this->session->getAuth()]);
    }

    public function check(Request $request): void 
    {
        $loginForm = new LoginForm();
        $loginForm->email = $request->get('email');
        $loginForm->password = $request->get('password');

        $user = $loginForm->login();
        $this->session->setAuth($user);
        $this->writeSuccessResponse();
    }
}