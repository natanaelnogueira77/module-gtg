<?php 
    $this->layout("themes/courses-master/_theme", [
        'title' => 'Redefinir Senha | ' . SITE,
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

        <h2>Redefinir Senha</h2>

        <?php if(!isset($code)): ?>
        <form class="form-default" action="<?= $router->route('reset-password.index') ?>" method="post">
            <div class="form-input">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email" 
                    class="form-control <?= $errors['email'] ? 'is-invalid' : '' ?>" 
                    value="<?= $email ?>" required>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="Enviar">
            </div>
        </form>
        <?php else: ?>
        <form class="form-default" action="<?= $router->route('reset-password.verify') ?>" method="post">
            <div class="form-input">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua nova senha" 
                    class="form-control <?= $errors['password'] ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            </div>

            <div class="form-input">
                <label for="confirm_password">Confirmar Senha</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                    class="form-control <?= $errors['confirm_password'] ? 'is-invalid' : '' ?>"
                    placeholder="Digite novamente sua senha" required>
                <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
            </div>

            <div class="form-input pt-30">
                <input type="submit" value="Redefinir">
            </div>
        </form>
        <?php endif; ?>
    </div>
</main>