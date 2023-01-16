<?php 
    $this->layout("themes/architect-ui/_theme", [
        'title' => 'Usuários | ' . SITE
    ]);
?>

<?php $this->start('scripts'); ?>
<script src="<?= url('resources/js/admin/users/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => 'Lista de Usuários',
        'subtitle' => 'Segue abaixo a lista de usuários do Sistema',
        'icon' => 'pe-7s-users',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-users icon-gradient bg-info"> </i>
            Usuários
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <a class="btn btn-lg btn-primary" href="<?= $router->route('admin.users.create') ?>">
                    Criar Usuário
                </a>
            </div>
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