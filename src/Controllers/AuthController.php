<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\{Config, User};
use Src\Models\LoginForm;
use Src\Views\LayoutFactory;
use Src\Views\Pages\AuthPage;

class AuthController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('auth', [
            'layout' => LayoutFactory::createMain(
                sprintf(_('Login | %s'), $this->appData['app_name'])
            ),
            'page' => new AuthPage(
                loginImageURL: Config::getLoginImageURL(),
                redirectURL: $request->get('redirect')
            )
        ]);
    }

    public function login(Request $request): void 
    {
        $loginForm = new LoginForm();
        $loginForm->email = $request->get('email');
        $loginForm->password = $request->get('password');

        $user = $loginForm->login();
        $this->session->setAuth($user);

        $this->setSuccessFlash(sprintf(_("Welcome, %s!"), $user->name));
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