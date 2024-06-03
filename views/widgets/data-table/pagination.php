<?php 
    $selectedPage = $activeRecordList->getPageToShow();
    $limit = $activeRecordList->getLimit();
    $totalRows = $activeRecordList->getResultsCount();
    $totalPages = $activeRecordList->getTotalPages();

    $startingValueForPageLooping = $selectedPage - 5 >= 1 ? (
        $selectedPage >= $totalPages - 5 ? ($totalPages > 10 ? $totalPages - 10 : 1) : $selectedPage - 5
    ) : 1;

    $isPageNumberOnLoopingCondition = fn($pageNumber) => $pageNumber <= $totalPages && $pageNumber >= $selectedPage - (
        $selectedPage >= $totalPages - 5 ? 10 - ($totalPages - $selectedPage) : 5
    ) && $pageNumber <= $selectedPage + (
        $selectedPage <= 5 ? 10 - $selectedPage : 5
    );
?>

<div class="row">
    <div class="col-sm-12 col-md-4 mb-3">
        <div>
            <?= 
                sprintf(
                    _('Mostrando %s à %s de %s resultado(s)'), 
                    $limit * ($selectedPage - 1) + 1, 
                    $selectedPage != $totalPages ? $limit * $selectedPage : $totalRows, 
                    $totalRows
                )
            ?>
        </div>
    </div>

    <?php if($totalPages > 1): ?>
    <div class="col-sm-12 col-md-8 mb-3">
        <nav>
            <ul class="pagination justify-content-end flex-wrap">
                <li class="page-item <?= $selectedPage > 1 ? '' : 'disabled' ?>">
                    <a class="page-link" dt-page="<?= ($selectedPage - 1) ?>"><?= _('Anterior') ?></a>
                </li>

                <?php 
                for(
                    $i = $startingValueForPageLooping; 
                    $isPageNumberOnLoopingCondition($i);
                    $i++
                ):
                ?>
                <li class="page-item <?= $i == $selectedPage ? 'active' : '' ?>">
                    <a class="page-link" dt-page="<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= $selectedPage < $totalPages ? '' : 'disabled' ?>">
                    <a class="page-link" dt-page="<?= ($selectedPage + 1) ?>"><?= _('Próxima') ?></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>