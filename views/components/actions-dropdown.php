<div class="dropup d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" 
        data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
        <?= _('Ações') ?>
    </button>

    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
        <h6 tabindex="-1" class="dropdown-header"><?= _('Ações') ?></h6>
        <?= $view->getChildren(); ?>
    </div>
</div>