<div class="d-flex">
    <div class="mt-1">
        <img width="40" class="rounded-circle" src="<?= $component->getImageURL() ?>">
    </div>
    <div class="mx-2">
        <strong><?= $component->getTitle() ?></strong>
        <?php if($component->hasSubtitle()): ?>
        <div class="opacity-7"><?= $component->getSubtitle() ?></div>
        <?php endif; ?>
    </div>
</div>