<?php $lang = getLang()->setFilepath('views/components/data-table-filters')->getContent() ?>
<div class="d-flex justify-content-between">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text"><?= $lang->get('rows') ?></span>
            </div>
            <select form="<?= $formId ?>" name="limit" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text"><?= $lang->get('search') ?></span>
            </div>
            <input form="<?= $formId ?>" type="search" name="search" 
                placeholder="<?= $lang->get('search_placeholder') ?>" class="form-control">
        </div>
    </div>
</div>