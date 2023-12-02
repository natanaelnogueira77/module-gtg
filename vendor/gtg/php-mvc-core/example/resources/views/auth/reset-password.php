<?php 
    $this->layout("themes/main/_theme", [
        'title' => sprintf('Reset Password | %s', $appData['app_name'])
    ]);
?>

<main>
    <h2>Reset Password</h2>

    <?php if(!isset($code)): ?>
    <form action="<?= $router->route('resetPassword.index') ?>" method="post">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Type your email..." 
            value="<?= $forgotPasswordForm->email ?>" required>
        <small>
            <?= $forgotPasswordForm->hasError('email') ? $forgotPasswordForm->getFirstError('email') : '' ?>
        </small>

        <input type="submit" value="Send">
    </form>
    <?php else: ?>
    <form action="<?= $router->route('resetPassword.verify', ['code' => $code]) ?>" method="post">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" placeholder="Type your new password..." required>
        <small>
            <?= $resetPasswordForm->hasError('password') ? $resetPasswordForm->getFirstError('password') : '' ?>
        </small>

        <label for="password_confirm">Confirm New Password</label>
        <input type="password" id="password_confirm" name="password_confirm" 
            placeholder="Type the new password again..." required>
        <small>
            <?= $resetPasswordForm->hasError('password_confirm') ? $resetPasswordForm->getFirstError('password_confirm') : '' ?>
        </small>

        <input type="submit" value="Reset Password">
    </form>
    <?php endif; ?>
</main>