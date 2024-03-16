<?php 
    $this->insert('emails/partials/header', [
        'logo' => $logo, 
        'title' => $appData['app_name']
    ]);
?>
<div style="margin-top: 20px; padding-bottom: 20px;">
    <h2><?= _('Reset Password') ?></h2>
    <p><?= sprintf(_("We received an password reset request from \"%s\" to this email. 
        If it weren't you, ignore this email. If else, click on the link below:"), $appData['app_name']) ?></p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $router->route('resetPassword.verify', array_merge([
            'token' => $user->token
        ], $redirect ? ['redirect' => $redirect] : [])) ?>">
            <?= _('Click here to reset your password') ?>
        </a>
    </p>
</div>