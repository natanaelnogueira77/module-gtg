<?php

namespace Src\Example\App\Web;

use Src\Example\App\Web\CTemplate;
use Src\Core\Models\Config;
use Src\Core\Models\User;
use Src\Core\System\ChangePassword;

class CChangePassword extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $exception = null;
        $errors = [];

        $background = Config::getMetaByName('login_img');

        if(count($data) > 0) {
            $redefine = new ChangePassword($data);
            try {
                $user = $redefine->checkEmail();
                $this->setEmailTemplate('redefine_password');
                $logo = Config::getMetaByName('logo');

                $this->sendEmail($user->email, $user->name, [
                    'user_name' => $user->name,
                    'user_slug' => $user->slug,
                    'user_email' => $user->email,
                    'user_token' => $user->token,
                    'logo' => url($logo)
                ]);
                
                addSuccessMsg("Um email foi enviado para {$user->email}. 
                    Verifique para poder redefinir sua senha.");
                header('Location: ' . url());
                exit();
            } catch(\Exception $e) {
                $exception = $e;
                if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                    $errors = $exception->getErrors();
                }
            }
        }

        $this->loadView('page', [
            'title' => 'Redefinir Senha | ' . SITE,
            'noHeader' => true,
            'noFooter' => true,
            'template' => $this->getTemplateView('change-password', $data + [
                'background' => $background,
                'errors' => $errors
            ]),
            'exception' => $exception
        ]);
    }

    public function verify(array $data): void 
    {
        $this->addMainData();
        $exception = null;
        $code = $data['code'];
        $errors = [];

        $background = Config::getMetaByName('login_img');

        if($code) {
            $userToken = User::getOne(['token' => $code]);
            if(!$userToken) {
                addErrorMsg('Esse cógido é inválido!');
                header('Location: ' . url());
                exit();
            } else {
                if(isset($data['redefine_senha']) || isset($data['confirm_redefine_senha'])) {
                    $redefinir = new ChangePassword($data);
                    try {
                        $redefinir->checkPassword($code);
                        $userInfo = User::getById($userToken->id);

                        $this->setSessionUser($userInfo);
    
                        addSuccessMsg('Senha redefinida com sucesso!');
                        header('Location: ' . url());
                        exit();
                    } catch(\Exception $e) {
                        $exception = $e;
                        if((new \ReflectionClass($exception))->getShortName() == 'ValidationException') {
                            $errors = $exception->getErrors();
                        }
                    }
                }
            }
        }

        $this->loadView('page', [
            'title' => 'Redefinir Senha | ' . SITE,
            'noHeader' => true,
            'noFooter' => true,
            'template' => $this->getTemplateView('change-password', $data + [
                'code' => $code,
                'background' => $background,
                'errors' => $errors
            ]),
            'exception' => $exception
        ]);
    }
}