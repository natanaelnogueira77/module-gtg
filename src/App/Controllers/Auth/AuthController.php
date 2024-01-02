<?php

namespace Src\App\Controllers\Auth;

use GTG\MVC\Controller;
use Src\Components\FileSystem;
use Src\Models\Config;
use Src\Models\LoginForm;

class AuthController extends Controller 
{
    public function index(array $data): void 
    {
        $data = array_merge($data, filter_input_array(INPUT_GET, FILTER_DEFAULT));
        $configMetas = (new Config())->getGroupedMetas([
            Config::KEY_LOGO, 
            Config::KEY_LOGO_ICON, 
            Config::KEY_LOGIN_IMG
        ]);

        $loginForm = new LoginForm();
        if($this->request->isPost()) {
            $loginForm->loadData([
                'email' => $data['email'],
                'password' => $data['password']
            ]);

            if($user = $loginForm->login()) {
                $this->session->setAuth($user);
                $this->session->setFlash('success', sprintf(_("Seja bem-vindo(a), %s!"), $user->name));
                if(isset($data['redirect'])) {
                    $this->response->redirect(url($data['redirect']));
                } else {
                    if($user->isAdmin()) {
                        $this->redirect('admin.index');
                    } else {
                        $this->redirect('user.index');
                    }
                }
            } else {
                $this->session->setFlash('error', _('Usuário e/ou senha inválidos!'));
            }
        }

        $this->render('auth/login', [
            'background' => FileSystem::getLink($configMetas[Config::KEY_LOGIN_IMG]),
            'logo' => FileSystem::getLink($configMetas[Config::KEY_LOGO]),
            'shortcutIcon' => FileSystem::getLink($configMetas[Config::KEY_LOGO_ICON]),
            'redirect' => $data['redirect'],
            'loginForm' => $loginForm
        ]);
    }

    public function logout(array $data): void 
    {
        $this->session->removeAuth();
        $this->redirect('auth.index');
    }
}