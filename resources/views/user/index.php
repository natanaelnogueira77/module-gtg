<?php 
    $this->layout("themes/architect-ui/_theme", [
        'title' => sprintf(_('Painel do Usuário | %s'), SITE)
    ]);
?>

<?php $this->start('scripts'); ?>
<script src="<?= url('resources/js/user/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => _('Painel do Usuário'),
        'subtitle' => _('Informações sobre sua atividade no sistema'),
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
    <?php endforeach; ?>
</div>
<?php endif; ?>