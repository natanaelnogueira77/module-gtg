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
        $lang = getLang()->setFilepath('controllers/auth/reset-password')->getContent()->setBase('index');

        if(count($data) > 0) {
            $forgotPassword = new ForgotPassword($data['email']);
            try {
                $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
                $resp = $recaptcha->setExpectedHostname(RECAPTCHA['host'])->verify($data['g-recaptcha-response']);
                if(!$resp->isSuccess()) {
                    throw new AppException($lang->get('recaptcha_error'));
                }

                $user = $forgotPassword->verify();
                if(!$user) {
                    throw $forgotPassword->error();
                }
                
                $email = new Email();
                $email->add($lang->get('email.subject'), $this->getView('emails/reset-password', [
                    'user' => $user,
                    'logo' => url($config['logo'])
                ]), $user->name, $user->email);
                
                if(!$email->send()) {
                    $this->throwException($email->error()->getMessage());
                }
                
                addSuccessMsg($lang->get('success', ['user_email' => $user->email]));
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
        $lang = getLang()->setFilepath('controllers/auth/reset-password')->getContent()->setBase('verify');
        
        $user = User::getByToken($data['code']);
        if(!$user) {
            addErrorMsg($lang->get('no_user'));
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

                addSuccessMsg($lang->get('success'));
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