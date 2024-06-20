
<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-users icon-gradient bg-info"> </i>
            <?= _('Lista de Usuários') ?>
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <?= $view->getActionButton() ?>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form id="<?= $view->filtersFormId ?>">
            <?= $view->getFilters() ?>
            <div class="form-row"> 
                <div class="form-group col-md-4 col-sm-6">
                    <label><?= _('Nível de Permissão') ?></label>
                    <select name="userType" class="form-control">
                        <option value=""><?= _('Todas as Permissões') ?></option>
                        <?php 
                            if($view->userTypes) {
                                foreach($view->userTypes as $userTypeId => $userType) {
                                    echo "<option value=\"{$userTypeId}\">{$userType}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </form>

        <div id="<?= $view->tableId ?>" data-action="<?= $view->action ?>">
            <div class="d-flex justify-content-around p-5">
                <div class="spinner-grow text-info" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    LIBRARY.set('usersPage', {
        actionButtonId: <?= json_encode($view->actionButtonId) ?>,
        formId: <?= json_encode($view->formId) ?>,
        modalId: <?= json_encode($view->modalId) ?>,
        tableId: <?= json_encode($view->tableId) ?>,
        filtersFormId: <?= json_encode($view->filtersFormId) ?>
    });
</script>