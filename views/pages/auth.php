<?php $this->layout('layouts/main', ['theme' => $theme]); ?>

<main class="login-body">
    <div class="full-background" style="background-image: url('<?= $theme->getBackgroundImageURL() ?>')"></div>
    <form id="main-login-form" class="form-default" action="<?= $router->route('auth.index') ?>" method="post">
        <?php if($redirect): ?>
        <input type="hidden" name="redirect" value="<?= $redirect ?>">
        <?php endif; ?>
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= $theme->getLogoIconURL() ?>" alt="">
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

            <a href="<?= $router->route('resetPassword.index') ?>" class="forget">
                <?= _('Esqueceu a senha?') ?>
            </a>
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
<script>
    $(function () {
        const dynamicForm = App.getDynamicForm(
            $(`#main-login-form`)
        ).onSuccess(function(instance, response) {
            if(response.redirectURL) window.location.href = response.redirectURL;
        }).apply();
    });
</script>
<?php $this->end(); ?>