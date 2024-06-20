<?php if($view->isHeading): ?> 
<li class="app-sidebar__heading"><?= $view->text ?></li>
<?php else: ?>
<li>
    <a href="<?= $view->url ?>" class="<?= $view->getStyles() ?>">
        <?php if($view->iconClass): ?>
        <i class="metismenu-icon <?= $view->iconClass ?>"></i>
        <?php endif; ?>

        <?= $view->text ?>
        <?php if($view->getChildren()): ?>
        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        <?php endif; ?>
    </a>
    <?php if($view->getChildren()): ?>
    <ul>
        <?= $view->getChildren() ?>
    </ul>
    <?php endif; ?>
</li>
<?php endif; ?>