<div class="widget-content">
    <div class="widget-content-outer">
        <div class="widget-content-wrapper mb-2">
            <div class="widget-content-left">
                <div class="widget-heading"><?= $data["title"] ?></div>
                <div class="widget-subheading"><?= $data["subtitle"] ?></div>
            </div>
            <div class="widget-content-right">
                <div class="widget-numbers <?= $data["info"]["color"] ?>">
                    <?= $data["info"]["text"] ?>
                </div>
            </div>
        </div>

        <?php if($data["progress"]): ?>
        <div class="widget-progress-wrapper">
            <div class="progress-bar-sm progress-bar-animated-alt progress">
                <div class="progress-bar progress-bar-animated progress-bar-striped <?= $data["progress"]["bg_color"] ?>" 
                    role="progressbar" aria-valuenow="<?= $data["progress"]["percentage"] ?>" aria-valuemin="0" 
                    aria-valuemax="100" style="width: <?= $data["progress"]["percentage"] ?>%;"></div>
            </div>
            <div class="progress-sub-label">
                <div class="sub-label-left"><?= $data["progress"]["sub_label"]["left"] ?></div>
                <div class="sub-label-right"><?= $data["progress"]["sub_label"]["right"] ?></div>
            </div>
        </div>
        <?php endif; ?>

        <?php 
        if($data["extra"]): 
            foreach($data["extra"] as $extra):
            ?>
            <div class="widget-content-wrapper mb-2">
                <?= $extra ?>
            </div>
            <?php 
            endforeach;
        endif;
        ?>
    </div>
</div>