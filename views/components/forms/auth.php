<main class="login-body">
    <div class="full-background" style="background-image: url('<?= $view->backgroundImage ?>')"></div>
    <form id="<?= $view->id ?>" class="form-default" action="<?= $view->action ?>" method="<?= $view->method ?>">
        <?php if($view->redirectUrl): ?>
        <input type="hidden" name="redirect" value="<?= $view->redirectUrl ?>">
        <?php endif; ?>
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= $view->logoIconUrl ?>" alt="">
                </a>
            </div>

            <h2><?= _('Entrar') ?></h2>

            <div class="form-input">
                <input type="email" name="email" placeholder="<?= _('Digite seu email') ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-input">
                <input type="password" name="password" placeholder="<?= _('Digite sua senha') ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-input pt-10">
                <input type="submit" value="<?= _('Entrar') ?>">
            </div>

            <a href="<?= $view->resetPasswordUrl ?>" class="forget">
                <?= _('Esqueceu a senha?') ?>
            </a>
        </div>
    </form>
</main>