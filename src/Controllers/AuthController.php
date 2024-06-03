<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\LoginForm;
use Src\Utils\ThemeUtils;

class AuthController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('auth', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Entrar | %s'), $this->appData['app_name'])
            ),
            'redirectURL' => $request->get('redirect')
        ]);
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