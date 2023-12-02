<thead>
    <?php 
    if($headers):
        foreach($headers as $info => $head):
        ?>
        <th class="align-middle <?= $head['classes'] ?>">
            <?php if($head['sort']): ?>
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0"><?= $head["text"] ?></p>
                <div class="d-flex flex-column">
                    <i class="icofont-arrow-up text-<?= $order['selected'] == $info && $order['type'] == "ASC" ? "secondary" : "light" ?>" 
                        data-order="<?= $info ?>" data-order-type="ASC" style="cursor: pointer;"></i>
                    <i class="icofont-arrow-down text-<?= $order['selected'] == $info && $order['type'] == "DESC" ? "secondary" : "light" ?>" 
                        data-order="<?= $info ?>" data-order-type="DESC" style="cursor: pointer;"></i>
                </div>
            </div>
            <?php else: ?>
            <?= $head['text'] ?>
            <?php endif; ?>
        </th>
        <?php 
        endforeach;
    endif;
    ?>
</thead>