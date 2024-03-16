<form id="<?= $widget->getId() ?>" action="<?= $widget->getAction() ?>" method="<?= $widget->getMethod() ?>">
    <div class="row">
        <div class="form-group col-md-6 mb-3">
            <label for="name">
                <?= _('Name') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Type a name.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <input type="text" name="name" placeholder="<?= _('Type a name...') ?>"
                class="form-control" value="<?= $widget->getUser()?->name ?? '' ?>" maxlength="100">
            <div class="invalid-feedback"></div>
        </div>
        
        <div class="form-group col-md-6 mb-3">
            <label for="email">
                <?= _('Email') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Type an valid email.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <input type="email" name="email" placeholder="<?= _('Type an email...') ?>"
                class="form-control" value="<?= $widget->getUser()?->email ?? '' ?>" maxlength="100">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    
    <div class="row">
        <?php if(!$widget->isAccountEdit()): ?>
        <div class="form-group col-md-5 mb-3">
            <label for="user_type">
                <?= _('Permission') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Choose an user permission.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <select name="user_type" class="form-control">
                <option value=""><?= _('Select...') ?></option>
                <?php 
                    foreach($widget->getUserTypes() as $userTypeId => $userType) {
                        echo "<option value=\"{$userTypeId}\">{$userType}</option>";
                    }
                ?>
            </select>
            <div class="invalid-feedback"></div>
        </div>
        <?php endif; ?>

        <div class="col-md-7 mb-3">
            <div id="update-password-area" class="align-middle">
                <div class="d-flex">
                    <p class="mb-0 mr-2"><strong><?= _('Do you want to change the password?') ?></strong></p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="update_password" value="1">
                        <label class="form-check-label"><?= _('Yes') ?></label>
                    </div>
        
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="update_password" value="0" checked>
                        <label class="form-check-label"><?= _('No') ?></label>
                    </div>
                </div>
            </div>

            <div id="password-area" class="form-group" style="<?= $widget->isAccountEdit() ? 'display: none;' : '' ?>">
                <label for="password">
                    <?= _('Password') ?>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" 
                        data-bs-title="<?= _('Type the password for accessing the account.') ?>">
                        <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                    </span>
                </label>
                <input type="password" name="password" class="form-control" 
                    placeholder="<?= _('Type a password...') ?>">
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
</form>