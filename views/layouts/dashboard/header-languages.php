<div class="header-btn-lg pr-2">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left">
                <div class="btn-group">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                        <img width="42" class="rounded-circle" src="<?= $view->currentImageUrl ?>" alt="">
                    </a>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <?= $view->getChildren() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>