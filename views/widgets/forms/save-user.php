<form id="save-user" action="<?= $isAccountEdit ? $router->route('edit.update') : '' ?>" 
    method="<?= $isAccountEdit ? 'put' : '' ?>">
    <div class="row">
        <div class="form-group col-md-6 mb-3">
            <label for="name">
                <?= _('Nome') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Digite um nome.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <input type="text" class="form-control" name="name" placeholder="<?= _('Digite um nome...') ?>" 
                maxlength="100" value="<?= $user?->name ?>">
            <div class="invalid-feedback"></div>
        </div>
        
        <div class="form-group col-md-6 mb-3">
            <label for="email">
                <?= _('Email') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Digite um email válido.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <input type="email" class="form-control" name="email" placeholder="<?= _('Digite um email...') ?>" 
                maxlength="100" value="<?= $user?->email ?>">
            <div class="invalid-feedback"></div>
        </div>
    </div>
    
    <div class="row">
        <?php if(!$isAccountEdit): ?>
        <div class="form-group col-md-5 mb-3">
            <label for="user_type">
                <?= _('Nível de Permissão') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="<?= _('Escolha um nível de permissão.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <select name="user_type" class="form-control">
                <option value=""><?= _('Selecionar...') ?></option>
                <?php 
                    foreach($userTypes as $userTypeId => $userType) {
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
                    <p class="mb-0 mr-2"><strong><?= _('Você deseja alterar a senha?') ?></strong></p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="update_password" value="1">
                        <label class="form-check-label"><?= _('Sim') ?></label>
                    </div>
        
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="update_password" value="0" checked>
                        <label class="form-check-label"><?= _('Não') ?></label>
                    </div>
                </div>
            </div>

            <div id="password-area" class="form-group" style="<?= $isAccountEdit ? 'display: none;' : '' ?>">
                <label for="password">
                    <?= _('Senha') ?>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" 
                        data-bs-title="<?= _('Digite a senha de acesso à sua conta.') ?>">
                        <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                    </span>
                </label>
                <input type="password" class="form-control" name="password" placeholder="<?= _('Digite uma senha...') ?>">
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12 mb-3">
            <label>
                <?= _('Imagem') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title='<?= _('Selecione a imagem do usuário.') ?>'>
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>

            <div id="avatar-image-area" data-uri="<?= $user?->getAvatarImageURI() ?>" data-url="<?= $user?->getAvatarImageURL() ?>"></div>
            <small class="text-danger" data-validation="avatar_image"></small>
        </div>
    </div>
</form>