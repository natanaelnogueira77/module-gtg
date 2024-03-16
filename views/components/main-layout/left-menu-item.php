<li class="nav-item w-100">
    <a class="<?= $component->getLinkStyles() ?>" 
        href="<?= $component->hasItems() ? "#{$component->getItemsListId()}" : $component->getURL() ?>" 
        <?= $component->hasItems() ? 'data-bs-toggle="collapse"' : '' ?>>
        <i class="fs-4 <?= $component->getTextColor() ?> <?= $component->getIcon() ?>"></i>
        <span class="ms-1 d-none d-md-inline <?= $component->getTextColor() ?>"><?= $component->getText() ?></span>
    </a>

    <?php if($component->hasItems()): ?>
    <ul id="<?= $component->getItemsListId() ?>" data-bs-parent="#left-menu" 
        class="collapse btn-toggle-nav list-unstyled fw-normal pb-1 small">
        <?= $component->getItems() ?>
    </ul>
    <?php endif; ?>
</li>