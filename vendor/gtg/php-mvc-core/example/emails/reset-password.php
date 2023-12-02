<table align="center" style="background-color: #363636; width: 100%; margin: 0 auto; border-radius: 5px;">
    <thead>
        <th style="text-align: center;">
            <h1 style="color: rgb(255, 255, 255); text-align: center;"><?= $appData['app_name'] ?></h1>
        </th>
    </thead>
</table>

<div style="margin-top: 20px; padding-bottom: 20px;">
    <h2>Reset password</h2>
    <p>
        <?= sprintf('
            We received a password recovery attempt from the site "%s" to this email.
            If you have not requested it, please disregard this email. If not, click the verification link below:', $appData['app_name']
        ) ?>
    </p>
</div>
<div>
    <p style="text-align: center;">
        <a href="<?= $router->route('resetPassword.verify', ['code' => $user->token]) ?>">
           Click to reset yor password
        </a>
    </p>
</div>