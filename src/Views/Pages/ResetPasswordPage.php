<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\Config;
use Views\Components\Forms\ResetPasswordForm;
use Views\Layouts\Main\Theme;
use Views\Widget;

class ResetPasswordPage extends Widget
{
    public function __construct(
        private readonly Request $request, 
        private readonly ?string $token = null
    )
    {}

    public function __toString(): string 
    {
        return new Theme(
            title: sprintf(_('Redefinir Senha | %s'), Application::getInstance()->appData['app_name']), 
            logoIconUrl: Config::getLogoIconURL(),
            body: new ResetPasswordForm(
                id: 'reset-password-form',
                action: $this->token ? Application::getInstance()->router->route(
                    'resetPassword.verify', 
                    array_merge([
                        'token' => $this->token
                    ], $this->request->get('redirect') ? ['redirect' => $this->request->get('redirect')] : [])
                ) : Application::getInstance()->router->route(
                    'resetPassword.index', 
                    $this->request->get('redirect') ? ['redirect' => $this->request->get('redirect')] : []
                ),
                method: 'post', 
                backgroundImage: Config::getLoginImageURL(), 
                logoIconUrl: Config::getLogoIconURL(),
                redirectUrl: $this->request->get('redirect'), 
                hasToken: $this->token ? true : false
            ), 
            scripts: [
                url('public/assets/js/Pages/ResetPasswordPage.js') => 'module'
            ]
        );
    }
}