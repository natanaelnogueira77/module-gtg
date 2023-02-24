<?php

namespace Src\App\Controllers\Auth;

use ReCaptcha\ReCaptcha;
use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\Email;
use Src\Components\ForgotPassword;
use Src\Components\ResetPassword;
use Src\Exceptions\AppException;
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
                $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
                $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                    ->setExpectedAction($_GET['action'])
                    ->setScoreThreshold(0.5)
                    ->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if(!$resp->isSuccess()) {
                    throw new AppException(_('O Teste do ReCaptcha falhou! Tente novamente.'));
                }

                $user = $forgotPassword->verify();
                if(!$user) {
                    throw $forgotPassword->error();
                }
                
                $email = new Email();
                $email->add(_('Redefinir Senha'), $this->getView('emails/reset-password', [
                    'user' => $user,
                    'logo' => url($config['logo'])
                ]), $user->name, $user->email);
                
                if(!$email->send()) {
                    $this->throwException($email->error()->getMessage());
                }
                
                addSuccessMsg(sprintf(_("Um email foi enviado para %s. Verifique para poder redefinir sua senha."), $user->email));
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
            addErrorMsg(_('Esse código é inválido!'));
            $this->redirect('login.index');
        }

        if(isset($data['password']) || isset($data['confirm_password'])) {
            $resetPassword = new ResetPassword($data);
            try {
                $user = $resetPassword->verify($data['code']);
                if(!$user) {
                    throw $resetPassword->error();
                }

                Auth::set($user);

                addSuccessMsg(_('Senha redefinida com sucesso!'));
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