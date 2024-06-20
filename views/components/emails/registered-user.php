<?= $view->getHeader() ?>
<div style="margin-top: 20px; padding-bottom: 20px;">
    <h4 style="text-align: center"><?= sprintf(_("Olá, %s!"), $view->user->name) ?></h4>
    <p style="text-align: center"><?= _("Aqui está as informações de seu registro:") ?></p>
    <br>
    <p><strong><?= _('Nome:') ?> </strong> <?= $view->user->name ?></p>
    <p><strong><?= _('Email:') ?> </strong> <?= $view->user->email ?></p>
    <p><strong><?= _('Senha:') ?> </strong> <?= $view->password ?></p>
<div>