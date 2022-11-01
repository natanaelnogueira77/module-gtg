<div class="drop<?= $dir ?> d-inline-block">
    <button type="button" aria-haspopup="true" aria-expanded="false" 
        data-toggle="dropdown" class="dropdown-toggle btn btn-<?= $btn["size"] ?> 
            btn<?= $btn["outline"] ? "-outline" : "" ?>-<?= $btn["color"] ?>">
        <?= $btn["text"] ?>
    </button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
        <?php 
        if($items): 
            foreach($items as $item):
                if($item["type"] == "header"):
                ?>
                <h6 tabindex="-1" class="dropdown-header"><?= $item["text"] ?></h6>
                <?php elseif($item["type"] == "button"): ?>
                <button type="button" tabindex="0" class="dropdown-item" <?= $item["attrs"] ?>>
                    <?= $item["text"] ?>
                </button>
                <?php elseif($item["type"] == "link"): ?>
                <a href="<?= $item["url"] ?>" <?= $item["blank"] ? "target=\"_blank\"" : "" ?> type="button" 
                    tabindex="0" class="dropdown-item">
                    <?= $item["text"] ?>
                </a>
                <?php elseif($item["type"] == "divider"): ?>
                <div tabindex="-1" class="dropdown-divider"></div>
                <?php 
                endif;
            endforeach;
        endif;
        ?>
    </div>
</div>