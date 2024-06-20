<form id="<?= $view->id ?>" action="<?= $view->action ?>" method="<?= $view->method ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="style">
                <?= _('Tema') ?>
                <span data-toggle="tooltip" data-placement="top" title="<?= _('Escolha o tema de cores do sistema.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <select id="style" name="style" class="form-control">
                <option value=""><?= _('Escolha o tema de cores do sistema...') ?></option>
                <option value="light" <?= $view->configValues['style'] == 'light' ? 'selected' : '' ?>>
                    <?= _('Tema Claro') ?>
                </option>
                <option value="dark" <?= $view->configValues['style'] == 'dark' ? 'selected' : '' ?>>
                    <?= _('Tema Escuro') ?>
                </option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group col-md-6">
            <label for="login_image">
                <?= _('Imagem de Fundo (Login)') ?>
                <span data-toggle="tooltip" data-placement="top" title="<?= _('Escolha a imagem que ficará de fundo na página de login.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <div id="login-img-area" data-url="<?= $view->configValues['login_image']['url'] ?>" 
                data-uri="<?= $view->configValues['login_image']['uri'] ?>"></div>
            <small class="text-danger" data-validation="loginImageURI"></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="logo">
                <?= _('Logo') ?>
                <span data-toggle="tooltip" data-placement="top" title="<?= _('Escolha a imagem que ficará como logo do sistema.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <div id="logo-area" data-url="<?= $view->configValues['logo']['url'] ?>" 
                data-uri="<?= $view->configValues['logo']['uri'] ?>"></div>
            <small class="text-danger" data-validation="logoURI"></small>
        </div>

        <div class="form-group col-md-6">
            <label for="logo_icon">
                <?= _('Ícone (Tamanho Recomendado: 512 x 512)') ?>
                <span data-toggle="tooltip" data-placement="top" title="<?= _('Escolha a imagem que ficará como ícone do sistema.') ?>">
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </label>
            <div id="logo-icon-area" data-url="<?= $view->configValues['logo_icon']['url'] ?>" 
                data-uri="<?= $view->configValues['logo_icon']['uri'] ?>"></div>
            <small class="text-danger" data-validation="logoIconURI"></small>
        </div>
    </div>
</form>