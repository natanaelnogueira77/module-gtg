<?php 
    $this->layout("themes/courses-master/_theme", [
        'title' => 'Entrar | ' . SITE,
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

            <h2>Entrar</h2>

            <div class="form-input">
                <input type="email" id="email" name="email" 
                    placeholder="Digite seu email" value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input">
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input d-flex justify-content-around">
                <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA['site_key'] ?>"></div>
            </div>

            <div class="form-input pt-10">
                <input type="submit" value="Entrar">
            </div>

            <a href="<?= $router->route('reset-password.index') ?>" class="forget">
                Esqueceu a senha?
            </a>
        </div>
    </form>
</main>