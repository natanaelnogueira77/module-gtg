<div class="app-header header-shadow <?= $backgroundColor ?> <?= $textColor ?>">
    <div class="app-header__logo">
        <img src="<?= $logoURL ?>" style="height: calc(100% - 6px); width: auto;">

        <?php if($hasLeft): ?>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>

    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>

    <div class="app-header__content">
        <div class="app-header-left">
            <?php $this->insert('widgets/layouts/dashboard/header-nav', $nav ?? []); ?>    
        </div>
        
        <?php $this->insert('widgets/layouts/dashboard/header-right', $right ?? []); ?>    
    </div>
</div>