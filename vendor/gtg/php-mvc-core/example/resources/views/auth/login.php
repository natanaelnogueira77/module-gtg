<?php 
    $this->layout("themes/main/_theme", [
        'title' => sprintf('Login | %s', $appData['app_name'])
    ]);
?>

<main>
    <form action="<?= $router->route('auth.index') ?>" method="post">
        <h2>Entrar</h2>

        <input type="email" id="email" name="email" 
            placeholder="Type your email..." value="<?= $loginForm->email ?>" required>
        <small>
            <?= $loginForm->hasError('email') ? $loginForm->getFirstError('email') : '' ?>
        </small>

        <input type="password" id="password" name="password" placeholder="Type your password..." required>
        <small>
            <?= $loginForm->hasError('password') ? $loginForm->getFirstError('password') : '' ?>
        </small>

        <input type="submit" value="Login">

        <a href="<?= $router->route('resetPassword.index') ?>">
            Forgot your password?
        </a>
    </form>
</main>