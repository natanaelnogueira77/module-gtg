<table align="center" style="background-color: #363636; width: 100%; margin: 0 auto; border-radius: 5px;">
    <thead>
        <th style="text-align: center">
            <img style="padding-left: 20px; padding-top: 10px; padding-bottom: 10px; padding-right: 20px;" 
                src="<?= $logo ?>" height="60px">
        </th>
        <th style="text-align: center;">
            <h1 style="color: rgb(255, 255, 255); text-align: center;"><?= SITE ?></h1>
        </th>
    </thead>
</table>

<div style="margin-top: 20px; padding-bottom: 20px;">
    <h2><?= _('Redefinir Senha') ?></h2>
    <p><?= sprintf(_('Recebemos uma tentativa de recuperação de senha do site "%s" para este e-mail. 
        Caso não tenha solicitado, desconsidere esse e-mail. Caso contrário, clique no link de verificação abaixo:'), SITE) ?></p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $router->route('reset-password.verify', ['code' => $user->token]) ?>">
            <?= _('Clique aqui para redefinir a senha') ?>
        </a>
    </p>
</div>