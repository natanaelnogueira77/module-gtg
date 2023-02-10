<?php $lang = getLang()->setFilepath('views/emails/user-register')->getContent(); ?>
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
    <h4 style="text-align: center"><?= $lang->get('heading1', ['user_name' => $user->name]) ?></h4>
    <p style="text-align: center"><?= $lang->get('text1') ?></p>
    <br>
    <p><strong><?= $lang->get('name') ?> </strong> <?= $user->name ?></p>
    <p><strong><?= $lang->get('email') ?> </strong> <?= $user->email ?></p>
    <p><strong><?= $lang->get('password') ?> </strong> <?= $password ?></p>
<div>