<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-user icon-gradient bg-malibu-beach"> </i>
            <?= _('Informações da Conta') ?>
            <span data-bs-toggle="tooltip" data-bs-placement="top" 
                data-bs-title='<?= _('Preencha os campos abaixo para editar os dados de sua conta. Então, clique em "Salvar".') ?>'>
                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
            </span>
        </div>
    </div>

    <div class="card-body">
        <?= $view->getForm() ?>
    </div>

    <div class="card-footer d-block text-center brb-15">
        <button type="submit" form="<?= $view->getForm()->id ?>" class="btn btn-lg btn-success"><?= _('Atualizar') ?></button>
        <a href="<?= $view->returnUrl ?>" class="btn btn-danger btn-lg"><?= _('Voltar') ?></a>
    </div>
</div>

<script>
    LIBRARY.set('editAccountPage', {
        formId: <?= json_encode($view->getForm()->id) ?>
    });
</script>