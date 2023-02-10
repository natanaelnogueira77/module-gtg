<?php 
    $lang = getLang()->setFilepath('views/auth/login')->getContent();
    $this->layout("themes/courses-master/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE]),
        'noHeader' => true,
        'noFooter' => true,
        'shortcutIcon' => $shortcutIcon,
        'preloader' => ['logo' => $logo]
    ]);
?>

<main class="login-body" data-vide-bg="<?= $background ?>">
    <form class="form-default" action="<?= $router->route('login.index') ?>" method="post">
        <?php if($redirect): ?>
        <input type="hidden" name="redirect" value="<?= $redirect ?>">
        <?php endif; ?>
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= $shortcutIcon ?>" alt="">
                </a>
            </div>

            <h2><?= $lang->get('card1.title') ?></h2>

            <div class="form-input">
                <input type="email" id="email" name="email" 
                    placeholder="<?= $lang->get('card1.email.placeholder') ?>" value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input">
                <input type="password" id="password" name="password" 
                    placeholder="<?= $lang->get('card1.password.placeholder') ?>" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input d-flex justify-content-around">
                <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA['site_key'] ?>"></div>
            </div>

            <div class="form-input pt-10">
                <input type="submit" value="<?= $lang->get('card1.submit.value') ?>">
            </div>

            <a href="<?= $router->route('reset-password.index') ?>" class="forget">
                <?= $lang->get('card1.forget_password') ?>
            </a>
        </div>
    </form>
</main>