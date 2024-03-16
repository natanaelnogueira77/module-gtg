<div class="row">
    <div class="col-sm-12 col-md-4 mb-3">
        <div>
            <?= 
                sprintf(
                    _('Showing %s to %s of %s result(s)'), 
                    $component->getFirstResultNumber(), 
                    $component->getLastResultNumber(), 
                    $component->getTotalRows()
                )
            ?>
        </div>
    </div>
    
    <?php if($component->getSelectedPage() > 1): ?>
    <div class="col-sm-12 col-md-8 mb-3">
        <nav>
            <ul class="pagination justify-content-end flex-wrap">
                <li class="page-item <?= $component->getSelectedPage() > 1 ? '' : 'disabled' ?>">
                    <a class="page-link" data-page="<?= ($component->getSelectedPage() - 1) ?>"><?= _('Previous') ?></a>
                </li>

                <?php 
                for(
                    $i = $component->getStartingValueForPageLooping(); 
                    $component->isPageNumberOnLoopingCondition($i);
                    $i++
                ):
                ?>
                <li class="page-item <?= $i == $component->getSelectedPage() ? 'active' : '' ?>">
                    <a class="page-link" data-page="<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= $component->getSelectedPage() < $component->getTotalPages() ? '' : 'disabled' ?>">
                    <a class="page-link" data-page="<?= ($component->getSelectedPage() + 1) ?>"><?= _('Next') ?></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>