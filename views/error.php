<?php $this->layout("layouts/error", ['title' => sprintf(_('%s | %s'), $error->getType(), $appData['app_name'])]); ?>

<div class="wrapper">
    <div class="box">
        <h1><?= $error->getType() ?></h1>
        <p><?= _('Lamentamos, mas ocorreu um erro ao tentar acessar esta página!') ?></p>
        <p><a href="<?= $router->route('home.index') ?>"><?= _('Voltar') ?></a></p>
    </div>
</div>