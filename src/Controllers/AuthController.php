<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Controllers\Controller;
use Src\Models\AR\Config;
use Src\Models\LoginForm;
use Src\Views\LayoutFactory;
use Src\Views\Widgets\Sections\LoginForm as LoginFormSection;

class AuthController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('auth', [
            'layout' => LayoutFactory::createMain(),
            'loginFormSection' => new LoginFormSection(
                formId: 'main-login-form',
                formAction: $this->router->route('auth.index'),
                formMethod: 'post',
                redirectURL: $request->get('redirect'),
                backgroundImageURL: Config::getLoginImageURL(),
                resetPasswordURL: $this->router->route(
                    'resetPassword.index', 
                    $request->get('redirect') ? ['redirect' => $request->get('redirect')] : []
                )
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