<session class="p-4 container-fluid">
    <div class="card shadow mb-4 br-15">
        <div class="card-header brt-15">
            <h5>
                <i class="icofont-gear text-primary"> </i>
                <?= _('System Options') ?>
            </h5>
        </div>

        <form id="<?= $widget->getFormId() ?>" action="<?= $widget->getFormAction() ?>" method="<?= $widget->getFormMethod() ?>">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="style">
                            <?= _('Theme') ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                data-bs-title="<?= _("Select the system's color scheme.") ?>">
                                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                            </span>
                        </label>
                        <select name="style" class="form-control">
                            <option value=""><?= _("Select the system's color scheme...") ?></option>
                            <option value="light" <?= $widget->getThemeStyle() == 'light' ? 'selected' : '' ?>>
                                <?= _('Light Theme') ?>
                            </option>
                            <option value="dark" <?= $widget->getThemeStyle() == 'dark' ? 'selected' : '' ?>>
                                <?= _('Dark Theme') ?>
                            </option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="login_image">
                            <?= _('Login Background Image') ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                data-bs-title="<?= _('Choose the image that will be shown on the background of the login page.') ?>">
                                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                            </span>
                        </label>
                        <div id="<?= $widget->getLoginImageId() ?>" data-url="<?= $widget->getLoginImageURL() ?>" 
                            data-uri="<?= $widget->getLoginImageURI() ?>"></div>
                        <small class="text-danger" data-validation="loginImageURI"></small>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="logo">
                            <?= _('Logo') ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                data-bs-title="<?= _('Choose the image that will the the logo of the system.') ?>">
                                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                            </span>
                        </label>
                        <div id="<?= $widget->getLogoId() ?>" data-url="<?= $widget->getLogoURL() ?>" 
                            data-uri="<?= $widget->getLogoURI() ?>"></div>
                        <small class="text-danger" data-validation="logoURI"></small>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="logo_icon">
                            <?= _('Icon (Recommended Size: 512 x 512)') ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                data-bs-title="<?= _('Choose the image that will the the icon of the system.') ?>">
                                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                            </span>
                        </label>
                        <div id="<?= $widget->getLogoIconId() ?>" data-url="<?= $widget->getLogoIconURL() ?>" 
                            data-uri="<?= $widget->getLogoIconURI() ?>"></div>
                        <small class="text-danger" data-validation="logoIconURI"></small>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-around brb-15">
                <button type="submit" class="btn btn-md btn-success btn-block"><?= _('Save Options') ?></button>
            </div>
        </form>
    </div>
</session>