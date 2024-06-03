<div class="header-btn-lg pr-2">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left">
                <div class="btn-group">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                        <img width="42" class="rounded-circle" src="<?= $currentImageURL ?>" alt="">
                    </a>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <?php 
                            foreach($list as $language) {
                                $this->insert('widgets/components/dropdown-item', [
                                    'type' => 'link', 
                                    'url' => $language['url'], 
                                    'content' => $language['name']
                                ]);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>