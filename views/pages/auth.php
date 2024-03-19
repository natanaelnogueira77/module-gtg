<?php 

use Src\Views\Widgets\Sections\LoginForm as LoginFormSection;

$loginFormSection = new LoginFormSection(
    formId: 'main-login-form',
    formAction: $router->route('auth.index'),
    formMethod: 'post',
    redirectURL: $page->getRedirectURL(),
    backgroundImageURL: $page->getLoginImageURL(),
    resetPasswordURL: $router->route(
        'resetPassword.index', 
        $page->getRedirectURL() ? ['redirect' => $page->getRedirectURL()] : []
    )
);

$this->layout("layouts/main", ['layout' => $layout]); 

$this->insert('widgets/sections/login-form', ['widget' => $loginFormSection]);

$this->start('scripts'); 
$this->insert('scripts/sections/login-form.js', ['widget' => $loginFormSection]);
$this->end();