<section class="py-5 container-fluid bg-image h-100" 
    style="background-image: url('<?= $widget->getBackgroundImageURL() ?>')">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-8 mb-4">
            <div class="card card-body">
                <h1 class="text-center mb-2"><?= _('Login') ?></h1>
                <form id="<?= $widget->getFormId() ?>" action="<?= $widget->getFormAction() ?>" 
                    method="<?= $widget->getFormMethod() ?>">
                    <?php if($widget->hasRedirectURL()): ?>
                    <input type="hidden" name="redirect" value="<?= $widget->getRedirectURL() ?>">
                    <?php endif; ?>

                    <div class="form-group mb-3">
                        <label class="form-label" for="email"><?= _('Email') ?></label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            placeholder="<?= _('Type your email') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="password"><?= _('Password') ?></label>
                        <input type="password" name="password" class="form-control form-control-lg"
                            placeholder="<?= _('Type your password') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <input type="submit" class="g-recaptcha btn btn-primary btn-lg" 
                        data-sitekey="<?= $appData['recaptcha']['site_key'] ?>"
                        data-callback='onSubmit' data-action='submit' value="<?= _('Login') ?>">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= $widget->getResetPasswordURL() ?>" 
                            class="text-body">
                            <?= _('Forgot your password?') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>