<?php

namespace Src\App\Controllers\Auth;

use ReCaptcha\ReCaptcha;
use ReflectionClass;
use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\FacebookLogin;
use Src\Components\GoogleLogin;
use Src\Components\Login;
use Src\Exceptions\AppException;
use Src\Models\Config;
use Src\Models\SocialUser;
use Src\Models\User;

class CLogin extends Controller 
{
    public function index(array $data): void 
    {
        $exception = null;
        $errors = [];
        $configMetas = (new Config())->getGroupedMetas(['logo', 'logo_icon', 'login_img']);

        if(count($data) > 0) {
            $login = new Login($data['email'], $data['password']);
            try {
                /* $recaptcha = new ReCaptcha(RECAPTCHA['secret_key']);
                $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                    ->setExpectedAction($_GET['action'])
                    ->setScoreThreshold(0.5)
                    ->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if(!$resp->isSuccess()) {
                    throw new AppException(_('O teste do ReCaptcha falhou! Tente novamente.'));
                } */

                $user = $login->verify();
                if(!$user) {
                    throw $login->error();
                }

                Auth::set($user);

                addSuccessMsg(sprintf(_("Seja bem-vindo(a), %s!"), $user->name));
                if(isset($data['redirect'])) {
                    header('Location: ' . url($data['redirect']));
                    exit();
                } else {
                    if($user->isAdmin()) {
                        $this->redirect('admin.index');
                    } else {
                        $this->redirect('user.index');
                    }
                }
            } catch(AppException $e) {
                $exception = $e;
                if((new ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/login', $data + [
            'background' => $configMetas && $configMetas['login_img'] ? url($configMetas['login_img']) : null,
            'logo' => $configMetas && $configMetas['logo'] ? url($configMetas['logo']) : null,
            'shortcutIcon' => $configMetas && $configMetas['logo_icon'] ? url($configMetas['logo_icon']) : null,
            'redirect' => $_GET['redirect'],
            'email' => $data['email'],
            'errors' => $errors,
            'exception' => $exception
        ]);
    }

    public function check(array $data): void 
    {
        $callback = [];
        
        try {
            $login = new Login($data['email'], $data['password']);
            $user = $login->verify();
            if(!$user) throw $login->error();

            Auth::set($user);
            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function logout(array $data): void 
    {
        Auth::destroy();
        $this->redirect('login.index');
    }

    public function expired(array $data): void 
    {
        $callback = [];

        $sess = Auth::get();
        $callback['success'] = false;
        if(!isset($sess)) $callback['success'] = true;

        $this->echoCallback($callback);
    }

    public function facebook(array $data): void
    {
        try {
            $facebook = new FacebookLogin([
                'id' => FACEBOOK['app_id'],
                'secret' => FACEBOOK['app_secret'],
                'redirect' => $this->getRoute('login.index'),
                'version' => FACEBOOK['app_version']
            ]);
    
            if(!$data['token']) {
                $callback['auth_url'] = $facebook->getAuthUrl();
                $this->echoCallback($callback);
                return;
            }
    
            $facebook->setToken($data['token']);
            $facebookUser = $facebook->getUser();

            $socialUser = SocialUser::getBySocialId($facebookUser->getId(), 'facebook');
            if(!$socialUser) {
                $socialUser = SocialUser::getBySocialEmail($facebookUser->getEmail(), 'facebook');
            }

            if(!$socialUser) {
                $dbUser = User::getByEmail($facebookUser->getEmail());

                if(!$dbUser) {
                    $dbUser = new User();
                    $pass = generatePassword(10);
                    $dbUser->setValues([
                        'utip_id' => 2,
                        'name' => $facebookUser->getFirstName(),
                        'email' => $facebookUser->getEmail(),
                        'password' => $pass,
                        'slug' => slugify($facebookUser->getFirstName() . rand(1, 10000))
                    ])->save();
                }
                
                Auth::set($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $facebookUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'facebook'
                ])->save();

                $callback['success'] = true;
            } else {
                $socialUser->getUser();
                if($socialUser->user) {
                    $this->setSessionUser($socialUser->user);
                }
            }

            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function google(array $data) 
    {
        try {
            $google = new GoogleLogin([
                'id' => GOOGLE['app_id'],
                'secret' => GOOGLE['app_secret'],
                'redirect' => $this->getRoute('login.index')
            ]);
    
            if(!$data['token']) {
                $callback['auth_url'] = $google->getAuthUrl();
                $this->echoCallback($callback);
                return;
            }
    
            $google->setToken($data['token']);
            $googleUser = $google->getUser();

            $socialUser = SocialUser::getBySocialId($googleUser->getId(), 'google');
            if(!$socialUser) {
                $socialUser = SocialUser::getBySocialEmail($googleUser->getEmail(), 'google');
            }

            if(!$socialUser) {
                $dbUser = User::getByEmail($googleUser->getEmail());

                if(!$dbUser) {
                    $dbUser = new User();
                    $pass = generatePassword(10);
                    $dbUser->setValues([
                        'utip_id' => 2,
                        'namse' => $googleUser->getFirstName(),
                        'email' => $googleUser->getEmail(),
                        'password' => $pass,
                        'slug' => slugify($googleUser->getFirstName() . rand(1, 10000))
                    ])->save();
                }
                
                Auth::set($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $googleUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'google'
                ])->save();

                $callback['success'] = true;
            } else {
                $socialUser->getUser();
                if($socialUser->user) {
                    $this->setSessionUser($socialUser->user);
                }
            }

            $callback['success'] = true;
        } catch(AppException $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}