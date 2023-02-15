<?php 
    $this->layout("themes/architect-ui/_theme", [
        'title' => sprintf(_('Usuários | %s'), SITE)
    ]);
?>

<?php $this->start('scripts'); ?>
<script> 
    const lang = {
        text1: <?php echo json_encode(_('Deseja realmente excluir este Usuário?')) ?>
    };
</script>
<script src="<?= url('resources/js/admin/users/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => _('Lista de Usuários'),
        'subtitle' => _('Segue abaixo a lista de usuários do Sistema'),
        'icon' => 'pe-7s-users',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-users icon-gradient bg-info"> </i>
            <?= _('Usuários') ?>
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <a class="btn btn-lg btn-primary" href="<?= $router->route('admin.users.create') ?>">
                    <?= _('Criar Usuário') ?>
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form id="filters">
            <?php $this->insert('components/data-table-filters', ['formId' => 'filters']); ?>
            <div class="form-row"> 
                <div class="form-group col-md-4 col-sm-6">
                    <label><?= _('Nível de Usuário') ?></label>
                    <select name="user_type" class="form-control">
                        <option value=""><?= _('Todos os Usuários') ?></option>
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