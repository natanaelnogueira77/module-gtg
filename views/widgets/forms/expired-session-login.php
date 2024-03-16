<form id="<?= $formId ?>" action="<?= $router->route('auth.check') ?>" method="post">
    <div class="form-group mb-3">
        <label class="form-label" for="email"><?= _('Email') ?></label>
        <input type="email" name="email" class="form-control" placeholder="<?= _('Type your email...') ?>" required>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="password"><?= _('Password') ?></label>
        <input type="password" name="password" class="form-control" placeholder="<?= _('Type your password...') ?>" required>
        <div class="invalid-feedback"></div>
    </div>
</form>