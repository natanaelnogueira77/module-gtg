<?php

namespace Src\Example\App\Web;

use Src\Example\App\Web\CTemplate;
use Src\Core\Models\User;

class CMain extends CTemplate 
{
    public function main(array $data): void 
    {
        $this->addMainData();
        $user = $this->getSessionUser();
        $exception = null;

        $this->loadView('page', [
            'title' => 'Home | ' . SITE,
            'template' => $this->getTemplateView('main'),
            'exception' => $exception
        ]);
    }

    public function logout(): void 
    {
        session_start();
        session_destroy();
        header('Location: ' . url());
    }

    public function redirect(): void 
    {
        session_start();
        $user = $this->getSessionUser();

        if($user) {
            if($user->isAdmin()) {
                header('Location: ' . url('admin'));
            } else {
                header('Location: ' . url('u'));
            }
        } else {
            header('Location: ' . url());
        }
    }

    public function checkLogin(array $data): void 
    {
        session_start();
        $callback = [];

        $login = new Login($data);
        try {
            $user = $login->checkLogin();
            $this->setSessionUser($user);
            $callback['success'] = true;

            if($data['redirect']) {
                $callback['redirect'] = url($data['redirect']);
            }
        } catch(\Exception $e) {
            $this->error = $e;
        }

        $this->echoCallback($callback);
    }

    public function validateSlug(array $data): void 
    {
        session_start();
        $user = $this->getSessionUser();
        $callback = [];

        if($data['slug']) {
            $checkUser = User::getBySlug($data['slug']);
            if($checkUser) {
                if($checkUser->id == $data['user_id']) {
                    $success = true;
                } else {
                    $success = false;
                }
            } else {
                $success = true;
            }
        } else {
            $callback['empty'] = true;
            $success = false;
        }

        $callback['success'] = $success;

        $this->echoCallback($callback);
    }
}