<?php

namespace Src\App\Controllers\Auth;

use Src\App\Controllers\Controller;
use Src\Components\Auth;
use Src\Components\FacebookLogin;
use Src\Components\GoogleLogin;
use Src\Components\Login;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\SocialUser;

class CLogin extends Controller 
{
    public function index(array $data): void 
    {
        $exception = null;
        $errors = [];
        $config = Config::getMetasByName(['logo', 'logo_icon', 'login_img']);

        if(count($data) > 0) {
            $login = new Login($data['email'], $data['password']);
            try {
                $user = $login->checkLogin();
                Auth::set($user);

                addSuccessMsg('Seja bem vindo, ' . $user->name . '!');
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
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('auth/login', [
            'background' => url($config['login_img']),
            'logo' => url($config['logo']),
            'shortcutIcon' => url($config['logo_icon']),
            'redirect' => $_GET['redirect'],
            'errors' => $errors,
            'exception' => $exception
        ]);
    }

    public function check(array $data): void 
    {
        session_start();
        $callback = [];

        $login = new Login($data);
        try {
            $user = $login->checkLogin();
            Auth::set($user);
            $callback['success'] = true;
        } catch(\Exception $e) {
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
        $sess = $this->getSessionUser();
        $callback = [];

        $callback['success'] = false;
        if(!isset($sess)) {
            $callback['success'] = true;
        }

        $this->echoCallback($callback);
    }

    public function facebook(array $data): void
    {
        try {
            $facebook = new FacebookLogin([
                'id' => FACEBOOK['app_id'],
                'secret' => FACEBOOK['app_secret'],
                'redirect' => FACEBOOK['app_redirect'],
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
                        'slug' => generateSlug($facebookUser->getFirstName() . rand(1, 10000))
                    ]);
                    $dbUser->save();
                }
                
                Auth::set($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $facebookUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'facebook'
                ]);

                $socialUser->save();
                $callback['success'] = true;
            } else {
                $socialUser->getUser();
                if($socialUser->user) {
                    $this->setSessionUser($socialUser->user);
                    $callback['success'] = true;
                }
            }
        } catch(\Exception $e) {
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
                'redirect' => GOOGLE['app_redirect']
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
                        'slug' => generateSlug($googleUser->getFirstName() . rand(1, 10000))
                    ]);
                    $dbUser->save();
                }
                
                Auth::set($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $googleUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'google'
                ]);

                $socialUser->save();
                $callback['success'] = true;
            } else {
                $socialUser->getUser();
                if($socialUser->user) {
                    $this->setSessionUser($socialUser->user);
                    $callback['success'] = true;
                }
            }
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }
}