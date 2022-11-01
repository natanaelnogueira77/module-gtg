<?php if($blocks): ?>
<div class="row">
    <?php foreach($blocks as $block): ?>
    <div class="col-md-4 mb-4">
        <a href="<?= $block['url'] ?>" style="text-decoration: none;">
            <div class="card shadow br-15" card-link>
                <div class="card-body text-dark">
                    <h3 class="text-center"><?= $block['title'] ?></h3>
                    <p class="text-center"><?= $block['text'] ?></p>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>