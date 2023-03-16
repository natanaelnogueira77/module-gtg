<nav>
    <ul class="pagination justify-content-center flex-wrap">
        <?php if($currPage > 1): ?>
        <li class="page-item">
            <a class="page-link" data-page="<?= ($currPage - 1) ?>"><?= _('Anterior') ?></a>
        </li>
        <?php endif; ?>

        <?php 
        if($pages): 
            for(
                $i = $currPage - 5 >= 1 
                ? (
                    $currPage >= $pages - 5 
                    ? $pages - 10
                    : $currPage - 5
                ) : 1; 
                $i <= $pages 
                    && $i >= $currPage - ($currPage >= $pages - 5 ? 10 - ($pages - $currPage) : 5) 
                    && $i <= $currPage + ($currPage <= 5 ? 10 - $currPage : 5);
                $i++
            ):
            ?>
            <li class="page-item <?= $i == $currPage ? 'active' : '' ?>">
                <a class="page-link" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
            <?php 
            endfor;
        endif;
        ?>

        <?php if($currPage < $pages): ?>
        <li class="page-item">
            <a class="page-link" data-page="<?= ($currPage + 1) ?>"><?= _('Próxima') ?></a>
        </li>
        <?php endif; ?>
    </ul>
</nav>