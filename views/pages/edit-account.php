<?php 
    $this->layout('layouts/dashboard', ['theme' => $theme]);
    $this->insert('widgets/layouts/dashboard/title', [
        'title' => _('Editar Conta'),
        'subtitle' => _('Edite os detalhes de sua conta logo abaixo'),
        'icon' => 'pe-7s-user',
        'iconColor' => 'bg-malibu-beach'
    ]);
?>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-user icon-gradient bg-malibu-beach"> </i>
            <?= _('Informações da Conta') ?>
            <span data-toggle="tooltip" data-placement="top" 
                title='<?= _('Preencha os campos abaixo para editar os dados de sua conta. Então, clique em "Salvar".') ?>'>
                <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
            </span>
        </div>
    </div>

    <div class="card-body">
        <?php 
            $this->insert('widgets/forms/save-user', [
                'isAccountEdit' => true,
                'user' => $session->getAuth(),
                'userTypes' => $userTypes
            ]) 
        ?>
    </div>

    <div class="card-footer d-block text-center brb-15">
        <button type="submit" form="save-user" class="btn btn-lg btn-success"><?= _('Atualizar') ?></button>
        <a href="<?= $router->route('user.index') ?>" class="btn btn-danger btn-lg"><?= _('Voltar') ?></a>
    </div>
</div>

<?php 
    $this->start('modals');
    $this->insert('widgets/modals/media-library');
    $this->end();

    $this->start('scripts'); 
    $this->insert('scripts/edit.js');
    $this->end(); 
?>