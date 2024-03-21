<div class="d-sm-flex justify-content-between">
    <?php if($component->hasLimitFilter()): ?>
    <div class="form-group mb-3">
        <div class="input-group">
            <span class="input-group-text"><?= _('Show') ?></span>
            <select name="limit" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="input-group-text"><?= _('Rows') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <?php if($component->hasSearchFilter()): ?>
    <div class="form-group mb-3">
        <input type="search" name="search" 
            placeholder="<?= _('Search for') ?>" class="form-control">
    </div>
    <?php endif; ?>
</div>