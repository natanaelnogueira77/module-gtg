<?php 
    $this->layout('layouts/error', [
        'code' => $error->getType()
    ]); 
?>

<div class="wrapper">
    <div class="box">
        <h1><?= $error->getType() ?></h1>
        <p>
            <a href="<?= $router->route('home.index') ?>"><?= _('Return') ?></a>
        </p>
    </div>
</div>