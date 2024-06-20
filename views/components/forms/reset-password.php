<main class="login-body">
    <div class="full-background" style="background-image: url('<?= $view->backgroundImage ?>')"></div>
    <div class="login-form mt-5">
        <div class="logo-login">
            <a href="#">
                <img src="<?= $view->logoIconUrl ?>" alt="">
            </a>
        </div>

        <h2><?= _('Redefinir Senha') ?></h2>

        <form id="<?= $view->id ?>" class="form-default" action="<?= $view->action ?>" method="<?= $view->method ?>">
            <input type="hidden" name="redirect" value="<?= $view->redirectUrl ?>">
            <?php if(!$view->hasToken): ?>
            <div class="form-input">
                <label for="email"><?= _('Email') ?></label>
                <input type="email" name="email" placeholder="<?= _('Digite seu email') ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="<?= _('Enviar') ?>">
            </div>
            <?php else: ?>
            <div class="form-input">
                <label for="password"><?= _('Nova Senha') ?></label>
                <input type="password" name="password" placeholder="<?= _('Digite sua nova senha') ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-input">
                <label for="passwordConfirm"><?= _('Confirmar Nova Senha') ?></label>
                <input type="password" name="passwordConfirm" placeholder="<?= _('Digite novamente a nova senha') ?>" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="<?= _('Redefinir') ?>">
            </div>
            <?php endif; ?>
        </form>
    </div>
</main>