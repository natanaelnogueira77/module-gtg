<?php

namespace Src\App\Controllers\Auth;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\Email;
use Src\Components\ForgotPassword;
use Src\Components\ResetPassword;
use Src\Models\Config;
use Src\Models\User;

class CResetPassword extends Controller 
{
    public function index(array $data): void 
    {
        $exception = null;
        $errors = [];
        $config = Config::getMetasByName(['logo', 'logo_icon', 'login_img']);

        if(count($data) > 0) {
            $forgotPassword = new ForgotPassword($data['email']);
            try {
                $user = $forgotPassword->check();
                
                $email = new Email();
                $email->add(
                    'Redefinir Senha',
                    $this->getView('emails/reset-password', [
                        'user' => $user,
                        'logo' => url($config['logo'])
                    ]),
                    $user->name,
                    $user->email
                )->send();
                
                if($email->error()) {
                    $this->throwException($email->error()->getMessage());
                }
                
                addSuccessMsg('Um email foi enviado para ' . $user->email . '. Verifique para poder redefinir sua senha.');
                $this->redirect('login.index');
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/reset-password', [
            'background' => url($config['login_img']),
            'logo' => url($config['logo']),
            'shortcutIcon' => url($config['logo_icon']),
            'errors' => $errors,
            'exception' => $exception
        ]);
    }

    public function verify(array $data): void 
    {
        $exception = null;
        $errors = [];
        
        $config = Config::getMetasByName(['logo', 'logo_icon', 'login_img']);
        
        $user = User::getByToken($data['code']);
        if(!$user) {
            addErrorMsg('Esse cógido é inválido!');
            $this->redirect('login.index');
        }

        if(isset($data['password']) || isset($data['confirm_password'])) {
            $resetPassword = new ResetPassword($data);
            try {
                $user = $resetPassword->check($data['code']);
                Auth::set($user);

                addSuccessMsg('Senha redefinida com sucesso!');
                $this->redirect('login.index');
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/reset-password', [
            'code' => $code,
            'background' => url($config['login_img']),
            'logo' => url($config['logo']),
            'shortcutIcon' => url($config['logo_icon']),
            'errors' => $errors,
            'exception' => $exception
        ]);
    }
}