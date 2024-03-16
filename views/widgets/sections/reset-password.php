<section class="py-5 container-fluid">
    <div class="row d-flex justify-content-around">
        <div class="col-md-8">
            <div class="card card-body">
                <h1 class="text-center mb-2"><?= _('Reset Your Password') ?></h1>
    
                <?php if(!$widget->hasToken()): ?>
                <form id="<?= $widget->getFormId() ?>" action="<?= $widget->getFormAction() ?>" 
                    method="<?= $widget->getFormMethod() ?>">
                    <?php if($widget->hasRedirectURL()): ?>
                    <input type="hidden" name="redirect" value="<?= $widget->getRedirectURL() ?>">
                    <?php endif; ?>

                    <div class="form-group form-group mb-4">
                        <label for="email"><?= _('Email') ?></label>
                        <input type="email" name="email" placeholder="<?= _('Type your email...') ?>" 
                            class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
    
                    <div class="d-block text-center">
                        <input type="submit" class="g-recaptcha btn btn-primary btn-md" 
                            data-sitekey="<?= $appData['recaptcha']['site_key'] ?>"
                            data-callback='onSubmit' data-action='submit' value="<?= _('Send') ?>">
                    </div>
                </form>
                <?php else: ?>
                <form id="<?= $widget->getFormId() ?>" action="<?= $widget->getFormAction() ?>" 
                    method="<?= $widget->getFormMethod() ?>">
                    <?php if($widget->hasRedirectURL()): ?>
                    <input type="hidden" name="redirect" value="<?= $widget->getRedirectURL() ?>">
                    <?php endif; ?>

                    <div class="form-group form-group mb-4">
                        <label for="password"><?= _('New Password') ?></label>
                        <input type="password" name="password" placeholder="<?= _('Type your new password...') ?>" 
                            class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
    
                    <div class="form-group form-group mb-4">
                        <label for="passwordConfirm"><?= _('Confirm New Password') ?></label>
                        <input type="password" name="passwordConfirm" class="form-control"
                            placeholder="<?= _('Type the new password again...') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
    
                    <div class="d-block text-center">
                        <input type="submit" class="btn btn-primary btn-md" value="<?= _('Send') ?>">
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>