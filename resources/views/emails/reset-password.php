<?php $lang = getLang()->setFilepath('views/emails/reset-password')->getContent(); ?>
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
    <h2><?= $lang->get('heading1') ?></h2>
    <p><?= $lang->get('text1', ['site_name' => SITE]) ?></p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $router->route('reset-password.verify', ['code' => $user->token]) ?>">
            <?= $lang->get('link1') ?>
        </a>
    </p>
</div>