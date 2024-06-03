<?php $this->insert('widgets/emails/partials/header'); ?>
<div style="margin-top: 20px; padding-bottom: 20px;">
    <h2><?= _('Redefinir Senha') ?></h2>
    <p><?= sprintf(_("Recebemos uma solicitação de redefinição de senha do site \"%s\" para este email. 
        Se não foi você, ignore esta mensagem. Caso contrário, click no link abaixo:"), $appData['app_name']) ?></p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $router->route('resetPassword.verify', array_merge([
            'token' => $user->token
        ], $redirect ? ['redirect' => $redirect] : [])) ?>">
            <?= _('Clique aqui para redefinir sua senha') ?>
        </a>
    </p>
</div>