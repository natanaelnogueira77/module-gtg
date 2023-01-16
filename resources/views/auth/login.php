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
        <div class="login-form mt-5">
            <div class="logo-login">
                <a href="#">
                    <img src="<?= $shortcutIcon ?>" alt="">
                </a>
            </div>

            <h2>Entrar</h2>

            <div class="form-input">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                    placeholder="Digite seu email" value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="Entrar">
            </div>

            <a href="<?= $router->route('reset-password.index') ?>" class="forget">
                Esqueceu a senha?
            </a>
        </div>
    </form>
</main>