<?php $this->layout("_theme"); ?>
<div class="wrapper">
    <div class="box">
        <h1><?= $errcode ?></h1>
        <p><?= $errmessage ?></p>
        <p><a href="<?= url() ?>">Clique aqui para Retornar</a></p>
    </div>
</div>