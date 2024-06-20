<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\Config;
use Views\Components\Forms\AuthForm;
use Views\Layouts\Main\Theme;
use Views\Widget;

class AuthPage extends Widget
{
    public function __construct(
        private readonly Request $request
    )
    {}

    public function __toString(): string 
    {
        return new Theme(
            title: sprintf(_('Entrar | %s'), Application::getInstance()->appData['app_name']), 
            logoIconUrl: Config::getLogoIconURL(),
            body: new AuthForm(
                id: 'main-login-form',
                action: Application::getInstance()->router->route('auth.index'),
                method: 'post', 
                backgroundImage: Config::getLoginImageURL(), 
                logoIconUrl: Config::getLogoIconURL(),
                resetPasswordUrl: Application::getInstance()->router->route('resetPassword.index'),
                redirectUrl: $this->request->get('redirect')
            ), 
            scripts: [
                url('public/assets/js/Pages/AuthPage.js') => 'module'
            ]
        );
    }
}