<?php 
    $this->layout("themes/error/_theme", [
        'code' => $code
    ]); 
?>

<div class="wrapper">
    <div class="box">
        <h1><?= $code ?></h1>
        <p><?= ErrorMessages::getByCode(intval($code)) ?></p>
        <p>
            <a href="<?= $router->route('home.index') ?>">Go Back</a>
        </p>
    </div>
</div>