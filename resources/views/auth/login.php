<?php 
    $this->layout("themes/courses-master/_theme", [
        'title' => sprintf(_('Entrar | %s'), SITE),
        'noHeader' => true,
        'noFooter' => true,
        'shortcutIcon' => $shortcutIcon,
        'preloader' => ['logo' => $logo]
    ]);
?>

<main class="login-body" data-vide-bg="<?= $background ?>">
    <form id="main-login-form" class="form-default" action="<?= $router->route('login.index') ?>" method="post">
        <?php if($redirect): ?>
        <input type="hidden" name="redirect" value="<?= $redirect ?>">
        <?php endif; ?>
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= $shortcutIcon ?>" alt="">
                </a>
            </div>

            <h2><?= _('Entrar') ?></h2>

            <div class="form-input">
                <input type="email" id="email" name="email" 
                    placeholder="<?= _('Digite seu email') ?>" value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input">
                <input type="password" id="password" name="password" 
                    placeholder="<?= _('Digite sua senha') ?>" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input pt-10">
                <input type="submit" class="g-recaptcha" data-sitekey="<?= RECAPTCHA['site_key'] ?>"
                    data-callback='onSubmit' data-action='submit' value="<?= _('Entrar') ?>">
            </div>

            <a href="<?= $router->route('reset-password.index') ?>" class="forget">
                <?= _('Esqueceu a senha?') ?>
            </a>
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
<script>
    function onSubmit(token) {
        document.getElementById("main-login-form").submit();
    }
</script>
<?php $this->end(); ?>