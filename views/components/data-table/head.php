<th class="<?= $component->getStyles() ?>">
    <?php if($component->isSortable()): ?>
    <div class="d-flex justify-content-between align-items-center">
        <p class="mb-0"><?= $component->getText() ?></p>
        <div class="d-flex flex-column">
            <i class="<?= $component->getArrowUpIconStyles() ?>" 
                dt-order-by="<?= $component->getColumnId() ?>" dt-order-type="ASC" style="cursor: pointer;"></i>
            <i class="<?= $component->getArrowDownIconStyles() ?>" 
                dt-order-by="<?= $component->getColumnId() ?>" dt-order-type="DESC" style="cursor: pointer;"></i>
        </div>
    </div>
    <?php else: ?>
    <?= $component->getText() ?>
    <?php endif; ?>
</th>