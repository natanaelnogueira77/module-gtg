<?php if($component->hasDivider()): ?>
<li><hr class="dropdown-divider"></li>
<?php endif; ?>

<li>
    <?php if($component->isHeader()): ?>
    <h6 class="dropdown-header"><?= $component->getText() ?></h6>
    <?php elseif($component->isButton()): ?>
    <button type="button" tabindex="0" class="dropdown-item" <?= $component->getAttributes() ?>>
        <?= $component->getText() ?>
    </button>
    <?php elseif($component->isLink()): ?>
    <a href="<?= $component->getURL() ?>" class="dropdown-item" <?= $component->getAttributes() ?>>
        <?= $component->getText() ?>
    </a>
    <?php endif; ?>
</li>