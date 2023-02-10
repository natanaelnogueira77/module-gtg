<?php 
    $lang = getLang()->setFilepath('views/error/index')->getContent();
    $this->layout("themes/error/_theme", [
        'code' => $code
    ]); 
?>
<div class="wrapper">
    <div class="box">
        <h1><?= $code ?></h1>
        <p><?= $message ?></p>
        <p>
            <a href="<?= $router->route('home.index') ?>"><?= $lang->get('return') ?></a>
        </p>
    </div>
</div>