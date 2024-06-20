<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
            <?= _('Configurações do Sistema') ?>
        </div>
    </div>

    <div class="card-body">
        <?= $view->getForm() ?>
    </div>

    <div class="card-footer d-flex justify-content-around brb-15">
        <button type="submit" form="<?= $view->formId ?>" class="btn btn-md btn-success btn-block">
            <?= _('Salvar Configurações') ?>
        </button>
    </div>
</div>