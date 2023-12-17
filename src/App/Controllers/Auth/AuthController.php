<?php

namespace Src\App\Controllers\Auth;

use GTG\MVC\Controller;
use Src\Components\FileSystem;
use Src\Models\Config;
use Src\Models\LoginForm;
use Src\Models\SocialUser;
use Src\Models\User;

class AuthController extends Controller 
{
    public function index(array $data): void 
    {
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
            'redirect' => $_GET['redirect'],
            'loginForm' => $loginForm
        ]);
    }

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

    public function logout(array $data): void 
    {
        $this->session->removeAuth();
        $this->redirect('auth.index');
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