<?php 
    $lang = getLang()->setFilepath('views/auth/reset-password')->getContent();
    $this->layout("themes/courses-master/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE]),
        'noHeader' => true,
        'noFooter' => true,
        'shortcutIcon' => $shortcutIcon,
        'preloader' => ['logo' => $logo]
    ]);
?>

<main class="login-body" data-vide-bg="<?= $background ?>">
    <div class="login-form mt-5">
        <div class="logo-login">
            <a href="#">
                <img src="<?= $shortcutIcon ?>" alt="">
            </a>
        </div>

        <h2><?= $lang->get('card1.title') ?></h2>

        <?php if(!isset($code)): ?>
        <form class="form-default" action="<?= $router->route('reset-password.index') ?>" method="post">
            <div class="form-input">
                <label for="email"><?= $lang->get('card1.email.label') ?></label>
                <input type="email" id="email" name="email" placeholder="<?= $lang->get('card1.email.placeholder') ?>" 
                    class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" 
                    value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input d-flex justify-content-around">
                <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA['site_key'] ?>"></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="<?= $lang->get('card1.send') ?>">
            </div>
        </form>
        <?php else: ?>
        <form class="form-default" action="<?= $router->route('reset-password.verify') ?>" method="post">
            <div class="form-input">
                <label for="password"><?= $lang->get('card1.password.label') ?></label>
                <input type="password" id="password" name="password" placeholder="<?= $lang->get('card1.email.placeholder') ?>" 
                    class="form-control <?= $errors['password'] ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input">
                <label for="confirm_password"><?= $lang->get('card1.confirm_password.label') ?></label>
                <input type="password" id="confirm_password" name="confirm_password" 
                    class="form-control <?= $errors['confirm_password'] ? 'is-invalid' : '' ?>"
                    placeholder="<?= $lang->get('card1.confirm_password.placeholder') ?>" required>
                <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="<?= $lang->get('card1.redefine') ?>">
            </div>
        </form>
        <?php endif; ?>
    </div>
</main>