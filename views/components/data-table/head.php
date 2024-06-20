<th class="align-middle">
    <?php if($view->isSortable): ?>
    <div class="d-flex justify-content-between align-items-center">
        <div><?= $view->content ?></div>
        <div class="d-flex flex-column">
            <i class="icofont-arrow-up text-<?= 
                $view->list->getOrderBy() == $view->columnName && $view->list->getOrderType() == 'ASC' ? 'secondary' : 'light' 
                ?>" dt-order-by="<?= $view->columnName ?>" dt-order-type="ASC" style="cursor: pointer;"></i>
            <i class="icofont-arrow-down text-<?= 
                $view->list->getOrderBy() == $view->columnName && $view->list->getOrderType() == 'DESC' ? 'secondary' : 'light' 
                ?>" dt-order-by="<?= $view->columnName ?>" dt-order-type="DESC" style="cursor: pointer;"></i>
        </div>
    </div>
    <?php else: ?>
    <?= $view->content ?>
    <?php endif; ?>
</th>