<?php 
    $this->insert('emails/partials/header', [
        'logo' => $logo, 
        'title' => $appData['app_name']
    ]);
?>

<div style="margin-top: 20px; padding-bottom: 20px;">
    <h4 style="text-align: center"><?= sprintf(_("Hi, %s!"), $user->name) ?></h4>
    <p style="text-align: center"><?= _("Here are your registration's informations:") ?></p>
    <br>
    <p><strong><?= _('Name:') ?> </strong> <?= $user->name ?></p>
    <p><strong><?= _('Email:') ?> </strong> <?= $user->email ?></p>
    <p><strong><?= _('Password:') ?> </strong> <?= $password ?></p>
<div>