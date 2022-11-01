<?php

namespace Src\Example\App\Web;

use Src\Example\App\Web\CTemplate;
use Src\Core\Models\Config;
use Src\Core\Models\User;
use Src\Core\System\Login;
use Src\Example\Models\SocialUser;
use Src\Example\Support\FacebookLogin;
use Src\Example\Support\GoogleLogin;

class CLogin extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        
        if($user) {
            if(isset($_GET['redirect'])) {
                header('Location: ' . url($_GET['redirect']));
            } else {
                header('Location: ' . url('redirect'));
            }
        }
        
        $exception = null;
        $background = Config::getMetaByName('login_img');

        if(count($data) > 0) {
            $login = new Login($data);
            try {
                $user = $login->checkLogin();
                $this->setSessionUser($user);

                if(isset($_GET['redirect'])) {
                    addSuccessMsg('Seja bem-vindo, ' . $user->name . '!');
                    header('Location: ' . url($_GET['redirect']));
                    exit();
                } else {
                    addSuccessMsg('Seja bem-vindo, ' . $user->name . '!');
                    header('Location: ' . url('redirect'));
                    exit();
                }
            } catch(\Exception $e) {
                $exception = $e;
            }
        }

        $urls = [
            'redefine_password' => url('redefinir-senha')
        ];

        $this->loadView('page', [
            'title' => 'Entrar | ' . SITE,
            'noHeader' => true,
            'noFooter' => true,
            'template' => $this->getTemplateView('login', $data + [
                'background' => $background,
                'urls' => $urls
            ]),
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
            $this->setSessionUser($user);
            $callback['success'] = true;
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function expiredSession(): void 
    {
        $sess = $this->getSessionUser();
        $callback = [];

        $callback['success'] = false;
        if(!isset($sess)) {
            $callback['success'] = true;
        }

        $this->echoCallback($callback);
    }

    public function facebook(array $data) 
    {
        session_start();

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
                $this->setEmailTemplate('user_registered');
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
                
                $this->setSessionUser($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $facebookUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'facebook'
                ]);

                $socialUser->save();

                if($email) {
                    $logo = Config::getMetaByName('logo');
                    $this->sendEmail($dbUser->email, $dbUser->name, [
                        'user_name' => $dbUser->name,
                        'user_slug' => $dbUser->slug,
                        'user_email' => $dbUser->email,
                        'user_token' => $dbUser->token,
                        'user_pass' => $pass,
                        'logo' => url($logo)
                    ]);
                }

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
        session_start();

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
                $this->setEmailTemplate('user_registered');
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
                
                $this->setSessionUser($dbUser);

                $socialUser = new SocialUser();
                $socialUser->setValues([
                    'usu_id' => $dbUser->id,
                    'social_id' => $googleUser->getId(),
                    'email' => $dbUser->email,
                    'social' => 'google'
                ]);

                $socialUser->save();

                if($email) {
                    $logo = Config::getMetaByName('logo');
                    $this->sendEmail($dbUser->email, $dbUser->name, [
                        'user_name' => $dbUser->name,
                        'user_slug' => $dbUser->slug,
                        'user_email' => $dbUser->email,
                        'user_token' => $dbUser->token,
                        'user_pass' => $pass,
                        'logo' => url($logo)
                    ]);
                }

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