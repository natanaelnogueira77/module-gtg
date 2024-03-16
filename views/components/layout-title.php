<section class="py-3 text-left container-fluid bg-light">
    <div class="row">
        <div class="col-md-12 mx-auto d-flex align-items-center">
            <?php if($component->hasIcon()): ?>
            <div class="border br-15 bg-white d-flex align-items-center text-center px-4 py-4">
                <i class="<?= $component->getIconStyles() ?>"></i>
            </div>
            <?php endif; ?>
            <div class="ms-2">
                <h2 class="fw-bold"><?= $component->getTitle() ?></h2>
                <p class="text-muted"><?= $component->getSubtitle() ?></p>
            </div>
        </div>
    </div>
</section>