<?php

namespace Src\App\Controllers\Auth;

use ReCaptcha\ReCaptcha;
use ReflectionClass;
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
        $configMetas = (new Config())->getGroupedMetas(['logo', 'logo_icon', 'login_img']);

        if(count($data) > 0) {
            $forgotPassword = new ForgotPassword($data['email']);
            try {
                /* $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
                $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                    ->setExpectedAction($_GET['action'])
                    ->setScoreThreshold(0.5)
                    ->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if(!$resp->isSuccess()) {
                    throw new AppException(_('O teste do ReCaptcha falhou! Tente novamente.'));
                } */

                $user = $forgotPassword->verify();
                if(!$user) {
                    throw $forgotPassword->error();
                }

                $user->saveMeta('last_pass_request', date('Y-m-d H:i:s'));
                
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
            } catch(AppException $e) {
                $exception = $e;
                if((new ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/reset-password', [
            'background' => $configMetas && $configMetas['login_img'] ? url($configMetas['login_img']) : null,
            'logo' => $configMetas && $configMetas['logo'] ? url($configMetas['logo']) : null,
            'shortcutIcon' => $configMetas && $configMetas['logo_icon'] ? url($configMetas['logo_icon']) : null,
            'errors' => $errors,
            'exception' => $exception
        ]);
    }

    public function verify(array $data): void 
    {
        $exception = null;
        $errors = [];
        $configMetas = (new Config())->getGroupedMetas(['logo', 'logo_icon', 'login_img']);
        
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
            } catch(AppException $e) {
                $exception = $e;
                if((new ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/reset-password', [
            'code' => $code,
            'background' => $configMetas && $configMetas['login_img'] ? url($config['login_img']) : null,
            'logo' => $configMetas && $configMetas['logo'] ? url($config['logo']) : null,
            'shortcutIcon' => $configMetas && $configMetas['logo_icon'] ? url($config['logo_icon']) : null,
            'errors' => $errors,
            'exception' => $exception
        ]);
    }
}