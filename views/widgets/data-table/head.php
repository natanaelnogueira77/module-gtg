<th class="align-middle">
    <?php if($head['isSortable']): ?>
    <div class="d-flex justify-content-between align-items-center">
        <div><?= $head['content'] ?></div>
        <div class="d-flex flex-column">
            <i class="icofont-arrow-up text-<?= 
                $activeRecordList->getOrderBy() == $head['columnName'] && $activeRecordList->getOrderType() == 'ASC' ? 'secondary' : 'light' 
                ?>" dt-order-by="<?= $head['columnName'] ?>" dt-order-type="ASC" style="cursor: pointer;"></i>
            <i class="icofont-arrow-down text-<?= 
                $activeRecordList->getOrderBy() == $head['columnName'] && $activeRecordList->getOrderType() == 'DESC' ? 'secondary' : 'light' 
                ?>" dt-order-by="<?= $head['columnName'] ?>" dt-order-type="DESC" style="cursor: pointer;"></i>
        </div>
    </div>
    <?php else: ?>
    <?= $head['content'] ?>
    <?php endif; ?>
</th>