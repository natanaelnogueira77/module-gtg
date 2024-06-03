<?php 
    $this->layout('layouts/dashboard', ['theme' => $theme]);
    $this->insert('widgets/layouts/dashboard/title', [
        'title' => _('Painel do Administrador'),
        'subtitle' => _('Relatórios e gerenciamento do sistema'),
        'icon' => 'pe-7s-home',
        'iconColor' => 'bg-malibu-beach'
    ]);
?>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-3 br-15">
            <div class="card-header-tab card-header-tab-animation card-header brt-15">    
                <div class="card-header-title">
                    <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
                    <?= _('Informações da Aplicação') ?>
                </div>
            </div>

            <div class="card-body">
                <div class="card-text"><?= sprintf(_('Versão: <strong>%s</strong>'), $appData['app_version']) ?></div>
            </div>

            <div class="card-footer d-block text-center brb-15">
                <button id="update-system" type="button" class="btn btn-md btn-success" 
                    data-action="<?= $router->route('admin.update') ?>" data-method="put" 
                    data-confirm-message="<?= _('Você tem certeza de que deseja atualizar o sistema para a última versão?') ?>">
                    <?= _('Atualizar Sistema') ?>
                </button>

                <button id="reset-system" type="button" class="btn btn-md btn-danger" 
                    data-action="<?= $router->route('admin.reset') ?>" data-method="delete"
                    data-confirm-message="<?= _('Você tem certeza de que deseja remover todos os dados? Essa ação não terá volta!') ?>">
                    <?= _('Remover Dados') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="main-card mb-3 card br-15">
            <?php if($userTypes): ?>
            <ul class="list-group list-group-flush">
                <?php foreach($userTypes as $utId => $userType): ?>
                <li class="list-group-item">
                    <div class="widget-content p-0">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper mb-2">
                                <div class="widget-content-left">
                                    <div class="widget-heading"><?= $userType ?></div>
                                    <div class="widget-subheading">
                                        <?= sprintf(_("Usuários com permissão de %s"), $userType) ?>
                                    </div>
                                </div>

                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">
                                        <?= $usersCount[$utId] ?? 0 ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
            <?= _('Configurações do Sistema') ?>
        </div>
    </div>

    <form id="update-config" action="<?= $router->route('admin.updateConfig') ?>" method="put">
        <div class="card-body">
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
                        <option value="light" <?= $configValues['style'] == 'light' ? 'selected' : '' ?>>
                            <?= _('Tema Claro') ?>
                        </option>
                        <option value="dark" <?= $configValues['style'] == 'dark' ? 'selected' : '' ?>>
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
                    <div id="login-img-area" data-url="<?= $configValues['login_image']['url'] ?>" 
                        data-uri="<?= $configValues['login_image']['uri'] ?>"></div>
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
                    <div id="logo-area" data-url="<?= $configValues['logo']['url'] ?>" 
                        data-uri="<?= $configValues['logo']['uri'] ?>"></div>
                    <small class="text-danger" data-validation="logoURI"></small>
                </div>

                <div class="form-group col-md-6">
                    <label for="logo_icon">
                        <?= _('Ícone (Tamanho Recomendado: 512 x 512)') ?>
                        <span data-toggle="tooltip" data-placement="top" title="<?= _('Escolha a imagem que ficará como ícone do sistema.') ?>">
                            <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                        </span>
                    </label>
                    <div id="logo-icon-area" data-url="<?= $configValues['logo_icon']['url'] ?>" 
                        data-uri="<?= $configValues['logo_icon']['uri'] ?>"></div>
                    <small class="text-danger" data-validation="logoIconURI"></small>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-around brb-15">
            <button type="submit" form="update-config" class="btn btn-md btn-success btn-block">
                <?= _('Salvar Configurações') ?>
            </button>
        </div>
    </form>
</div>

<?php 
    $this->start('modals');
    $this->insert('widgets/modals/media-library');
    $this->end();

    $this->start('scripts'); 
    $this->insert('scripts/admin.js');
    $this->end();
?>