<?= $view->getHeader() ?>
<div style="margin-top: 20px; padding-bottom: 20px;">
    <h2><?= _('Redefinir Senha') ?></h2>
    <p><?= sprintf(_("Recebemos uma solicitação de redefinição de senha do site \"%s\" para este email. 
        Se não foi você, ignore esta mensagem. Caso contrário, click no link abaixo:"), $view->siteUrl) ?></p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $view->getLink() ?>">
            <?= _('Clique aqui para redefinir sua senha') ?>
        </a>
    </p>
</div>