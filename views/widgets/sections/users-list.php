<?php use Src\Views\Components\DataTable\Filters; ?>
<section class="p-4 container-fluid">
    <div class="card shadow mb-4 br-15">
        <div class="card-header brt-15">
            <div class="d-flex justify-content-between">
                <h5>
                    <i class="icofont-users text-primary"> </i>
                    <?= _('Users') ?>
                </h5>
        
                <button id="<?= $widget->getButtonId() ?>" type="button" class="btn btn-sm btn-primary" 
                    data-action="<?= $router->route('users.store') ?>" data-method="post">
                    <i class="icofont-plus"> </i>
                    <?= _('Register User') ?>
                </button>
            </div>
        </div>
    
        <div class="card-body">
            <form id="<?= $widget->getFiltersFormId() ?>">
                <?php $this->insert('components/data-table/filters', ['component' => new Filters()]) ?>
                <div class="row"> 
                    <div class="form-group col-md-4 col-sm-6 mb-3">
                        <label><?= _('Permission') ?></label>
                        <select name="userType" class="form-control">
                            <option value=""><?= _('All Permissions') ?></option>
                            <?php 
                                foreach($widget->getUserTypes() as $userTypeId => $userType) {
                                    echo "<option value=\"{$userTypeId}\">{$userType}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </form>

            <div id="<?= $widget->getTableId() ?>" data-action="<?= $router->route('users.list') ?>">
                <div class="d-flex justify-content-around p-5">
                    <div class="spinner-grow text-secondary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>