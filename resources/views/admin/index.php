<?php 
    $this->layout("themes/architect-ui/_theme", [
        'title' => 'Administrador | ' . SITE
    ]);
?>

<?php $this->start('scripts'); ?>
<script src="<?= url('resources/js/admin/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => 'Painel do Administrador',
        'subtitle' => 'Relatórios e gerenciamento do sistema',
        'icon' => 'pe-7s-home',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-3 br-15">
            <div class="card-header-tab card-header-tab-animation card-header brt-15">    
                <div class="card-header-title">
                    <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
                    Módulo GTG
                </div>
            </div>

            <div class="card-body">
                <div class="card-text">Versão: <strong><?= $gtgVersion ?></strong></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="main-card mb-3 card br-15">
            <?php if($userTypes): ?>
            <ul class="list-group list-group-flush">
                <?php foreach($userTypes as $userType): ?>
                <li class="list-group-item">
                    <div class="widget-content p-0">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper mb-2">
                                <div class="widget-content-left">
                                    <div class="widget-heading"><?= $userType->name_plur ?></div>
                                    <div class="widget-subheading">
                                        <?= $userType->name_plur ?> do Sistema
                                    </div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">
                                        <?= $countUsers[$userType->id] ?? 0 ?>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content-wrapper">
                                <div class="widget-content-right">
                                    <button class="btn btn-lg btn-success" data-info="users" data-id="<?= $userType->id ?>">
                                        Ver <?= $userType->name_plur ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </div>
    </div>
</div>

<div id="panels_top"></div>

<div class="card shadow mb-4 panels br-15" id="panel_users" style="display: none;">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-investigator icon-gradient bg-night-sky"> </i>
            Usuários
        </div>
    </div>
    
    <div class="card-body">
        <form id="filters">
            <?php $this->insert('components/data-table-filters', ['formId' => 'filters']); ?>
            <div class="form-row"> 
                <div class="form-group col-md-4 col-sm-6">
                    <label>Nível de Usuário</label>
                    <select name="user_type" class="form-control">
                        <option value="">Todos os Usuários</option>
                        <?php 
                            if($userTypes) {
                                foreach($userTypes as $userType) {
                                    echo "<option value=\"{$userType->id}\">{$userType->name_plur}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <?php $this->insert('components/data-table-buttons', ['formId' => 'filters', 'clearId' => 'clear']); ?>
        </form>
    </div>
    <hr class="my-0">
    <div class="card-body">
        <div id="users" data-action="<?= $router->route('admin.users.list') ?>"></div>
    </div>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
            Configurações do Sistema
        </div>
    </div>

    <form id="system" action="<?= $router->route('admin.system') ?>" method="put">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="style">Tema</label>
                    <select id="style" name="style" class="form-control">
                        <option value="">Escolha o Tema de Cores do Sistema...</option>
                        <option value="light" <?= $configData['style'] == 'light' ? 'selected' : '' ?>>
                            Tema Claro
                        </option>
                        <option value="dark" <?= $configData['style'] == 'dark' ? 'selected' : '' ?>>
                            Tema Escuro
                        </option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Imagem de Fundo (Login)</label>
                    <div class="d-flex justify-content-around">
                        <img id="login_img_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['login_img']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="login_img" name="login_img" value="<?= $configData['login_img'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="login_img_upload">
                            <i class="icofont-upload-alt"></i> Subir Arquivo
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Logo</label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo" name="logo" value="<?= $configData['logo'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_upload">
                            <i class="icofont-upload-alt"></i> Subir Arquivo
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Ícone (Tamanho Recomendado: 512 x 512)</label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_icon_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo_icon']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo_icon" name="logo_icon" value="<?= $configData['logo_icon'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_icon_upload">
                            <i class="icofont-upload-alt"></i> Subir Arquivo
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-around brb-15">
            <input type="submit" class="btn btn-md btn-success btn-block" value="Salvar Configurações">
        </div>
    </form>
</div>