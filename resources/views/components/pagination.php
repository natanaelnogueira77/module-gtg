<nav>
    <ul class="pagination">
        <?php if($currPage > 1): ?>
        <li class="page-item"><a class="page-link" data-page="<?= ($currPage - 1) ?>">Anterior</a></li>
        <?php endif; ?>

        <?php 
        if($pages): 
            for($i = 1; $i <= $pages; $i++):
            ?>
            <li class="page-item <?= $i == $currPage ? 'active' : '' ?>">
                <a class="page-link" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
            <?php 
            endfor;
        endif;
        ?>

        <?php if($currPage < $pages): ?>
        <li class="page-item"><a class="page-link" data-page="<?= ($currPage + 1) ?>">Próximo</a></li>
        <?php endif; ?>
    </ul>
</nav>