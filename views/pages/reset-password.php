<?php 

use Src\Views\Widgets\Sections\ResetPassword as ResetPasswordSection;

$resetPasswordSection = new ResetPasswordSection(
    formId: 'reset-password-form',
    formAction: $page->getFormAction(),
    formMethod: 'post',
    redirectURL: $page->getRedirectURL(),
    hasToken: $page->hasToken()
);

$this->layout("layouts/main", ['layout' => $layout]); 

$this->insert('widgets/sections/reset-password', ['widget' => $resetPasswordSection]);

$this->start('scripts'); 
$this->insert('scripts/sections/reset-password.js', ['widget' => $resetPasswordSection]);
$this->end();