<?php 
    $this->layout('layouts/dashboard', ['theme' => $theme]);
    $this->insert('widgets/layouts/dashboard/title', [
        'title' => _('Lista de Usuários'),
        'subtitle' => _('Segue abaixo a lista de usuários do sistema'),
        'icon' => 'pe-7s-users',
        'iconColor' => 'bg-malibu-beach'
    ]);
?>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-users icon-gradient bg-info"> </i>
            <?= _('Lista de Usuários') ?>
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <button id="create-user" type="button" class="btn btn-lg btn-primary" 
                    data-action="<?= $router->route('users.store') ?>" data-method="post">
                    <i class="icofont-plus"></i>
                    <?= _('Cadastrar Usuário') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form id="filters">
            <?php $this->insert('widgets/data-table/filters', ['formId' => 'filters']); ?>
            <div class="form-row"> 
                <div class="form-group col-md-4 col-sm-6">
                    <label><?= _('Nível de Permissão') ?></label>
                    <select name="userType" class="form-control">
                        <option value=""><?= _('Todas as Permissões') ?></option>
                        <?php 
                            if($userTypes) {
                                foreach($userTypes as $userTypeId => $userType) {
                                    echo "<option value=\"{$userTypeId}\">{$userType}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </form>

        <div id="users" data-action="<?= $router->route('users.list') ?>">
            <div class="d-flex justify-content-around p-5">
                <div class="spinner-grow text-secondary" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->start('modals'); 
    $this->insert('widgets/modals/media-library');
    $this->insert('widgets/modals/save-user', ['userTypes' => $userTypes]);
    $this->end(); 

    $this->start('scripts'); 
    $this->insert('scripts/users.js');
    $this->end(); 
?>