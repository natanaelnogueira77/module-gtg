<header class="p-3 sticky-top shadow <?= $component->getBackgroundColor() ?>" style="z-index: 10000;">
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-start">
            <?php if($component->hasLeft()): ?>
            <button id="toggle-left-sidebar-button" class="btn btn-outline-secondary btn-md mx-2 active">
                <i class="icofont-navigation-menu"></i>
            </button>
            <?php endif; ?>

            <a href="#" class="d-flex align-items-center mb-0 <?= $component->getTextColor() ?> text-decoration-none mx-2">
                <img src="<?= $component->getLogoURL() ?>" width="auto" height="40">
            </a>

            <?php $this->insert('components/main-layout/header-menu', ['component' => $component->getMenu()]) ?>
            <?php $this->insert('components/main-layout/header-right', ['component' => $component->getRight()]) ?>
        </div>
    </div>
</header>