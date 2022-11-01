<div class="header-area header-transparent" style="background: linear-gradient(to bottom, #4949FF 0%, #7879FF 100%);">
    <div class="main-header">
        <div class="header-bottom  header-sticky" style="background: linear-gradient(to bottom, #4949FF 0%, #7879FF 100%);">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xl-2 col-lg-2">
                        <div class="logo">
                            <a href="#"><img src="<?= url($logo) ?>" alt="" height="60px"></a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <div class="menu-wrapper d-flex align-items-center justify-content-end">
                            <div class="main-menu d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <?php foreach($menu as $item): ?>                                                                                  
                                        <li>
                                            <a href="<?= url($item['url']) ?>"><?= $item['desc'] ?></a>
                                        </li>
                                        <?php endforeach ?>
                                        <?php 
                                        if($right["items"]):
                                            foreach($right["items"] as $item):
                                            ?>
                                            <li class="button-header">
                                                <a href="<?= $item["url"] ?>" class="btn btn3">
                                                    <?= $item["desc"] ?>
                                                </a>
                                            </li>
                                            <?php 
                                            endforeach;
                                        endif;
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->