<div class="header-btn-lg pr-0">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left">
                <div class="btn-group">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                        <img width="42" class="rounded-circle" src="<?= $imageURL ?>" alt="user">
                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                    </a>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <?php 
                            if($items) {
                                foreach($items as $item) {
                                    $this->insert('widgets/components/dropdown-item', $item);
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="widget-content-left ml-3 header-user-info">
                <div class="widget-heading"><?= $title ?></div>
                <div class="widget-subheading"><?= $subtitle ?></div>
            </div>
        </div>
    </div>
</div>