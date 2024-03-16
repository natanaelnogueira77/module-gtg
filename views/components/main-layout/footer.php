<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 px-2 border-top 
    <?= $component->getBackgroundColor() ?> mt-auto">
    <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 mx-2 mb-md-0 text-muted text-decoration-none lh-1">
            <img src="<?= $component->getLogoURL() ?>" width="auto" height="40" alt="">
        </a>
        <span class="mb-3 mb-md-0 <?= $component->getTextColor() ?>">
            <?= $component->getCopyrightText() ?>
        </span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
        <?php 
            if($component->hasSocials()) {
                foreach($component->getSocials() as $social) {
                    $this->insert('components/main-layout/social', ['component' => $social]);
                }
            }
        ?>
    </ul>
</footer>