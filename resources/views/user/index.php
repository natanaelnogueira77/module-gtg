<?php 
    $lang = getLang()->setFilepath('views/user/index')->getContent();
    $this->layout("themes/architect-ui/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE])
    ]);
?>

<?php $this->start('scripts'); ?>
<script> 
    const lang = <?php echo json_encode($lang->get('script')) ?>;
</script>
<script src="<?= url('resources/js/user/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => $lang->get('title2'),
        'subtitle' => $lang->get('subtitle'),
        'icon' => 'pe-7s-user',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

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