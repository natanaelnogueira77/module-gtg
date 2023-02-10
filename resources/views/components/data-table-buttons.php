<?php $lang = getLang()->setFilepath('views/components/data-table-buttons')->getContent() ?>
<div class="d-block text-center mb-2">
    <button form="<?= $formId ?>" type="submit" class="btn btn-outline-primary btn-lg">
        <i class="icofont-search"></i> <?= $lang->get('filter') ?>
    </button>
    <button type="button" id="<?= $clearId ?>" class="btn btn-outline-danger btn-lg">
        <i class="icofont-close"></i> <?= $lang->get('clean') ?>
    </button>
</div>