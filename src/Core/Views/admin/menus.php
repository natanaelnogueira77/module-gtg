<div class="card shadow br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-list icon-gradient bg-night-sky"> </i>
            Editar Menus
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <a class="btn btn-lg btn-primary" href="<?= $urls['creation'] ?>">Criar Menu</a>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card br-15">
    <ul class="list-group list-group-flush br-15">
        <?php 
        if($menus): 
            foreach($menus as $menu):
            ?>
            <li class="list-group-item">
                <div class="widget-content p-0">
                    <div class="widget-content-outer">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading"><?= $menu['name'] ?></div>
                            </div>
                            <div class="widget-content-right">
                                <div class="d-flex">
                                    <a href="<?= $menu["edit"] ?>" 
                                        class="btn btn-md btn-primary mr-2">Editar</a>
                                    <form action="<?= $menu['delete']['url'] ?>" 
                                        method="<?= $menu['delete']['method'] ?>" menu-delete>
                                        <input type="submit" class="btn btn-danger btn-md" value="Excluir">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <?php 
            endforeach;
        endif;
        ?>
    </ul>
</div>