<div class="header-btn-lg pr-0">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left">
                <div class="btn-group">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                        <img width="42" class="rounded-circle" src="<?= $view->avatarUrl ?>" alt="user">
                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                    </a>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <?= $view->getChildren() ?>
                    </div>
                </div>
            </div>

            <div class="widget-content-left ml-3 header-user-info">
                <div class="widget-heading"><?= $view->title ?></div>
                <div class="widget-subheading"><?= $view->subtitle ?></div>
            </div>
        </div>
    </div>
</div>