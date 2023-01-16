<?php 
    $this->layout("themes/architect-ui/_theme", [
        'title' => 'Editar Conta | ' . SITE
    ]);
?>

<?php $this->start('scripts'); ?>
<script src="<?= url('resources/js/user/edit.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => 'Editar Conta',
        'subtitle' => 'Edite os Detalhes de sua Conta logo abaixo',
        'icon' => 'pe-7s-user',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

<form id="save-user" action="<?= $router->route('user.edit.update') ?>" method="put">
    <div class="card shadow mb-4 br-15">
        <div class="card-header-tab card-header-tab-animation card-header brt-15">    
            <div class="card-header-title">
                <i class="header-icon icofont-user icon-gradient bg-malibu-beach"> </i>
                Informações da Conta
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" placeholder="Informe um nome..."
                        class="form-control" value="<?= $user->name ?>" maxlength="50">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="slug">Apelido</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" id="slug" name="slug" placeholder="Informe um apelido..."
                            class="form-control" value="<?= $user->slug ?>" maxlength="50">
                        <div id="slug-feedback" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Informe um email..."
                        class="form-control" value="<?= $user->email ?>"  maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12 align-middle">
                    <div class="d-flex">
                        <p class="mb-0 mr-2"><strong>Deseja alterar a Senha?</strong></p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" id="update_password1" value="1">
                            <label class="form-check-label" for="update_password1">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" id="update_password2" value="0" checked>
                            <label class="form-check-label" for="update_password2">Não</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row" id="password" style="display: none;">
                <div class="form-group col-md-6">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" 
                        placeholder="Digite uma senha" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="confirm_password">Confirmar Senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                        placeholder="Digite novamente a senha" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <input type="submit" class="btn btn-lg btn-success" value="Atualizar">
            <a href="<?= $router->route('user.index') ?>" class="btn btn-danger btn-lg">
                Voltar
            </a>
        </div>
    </div>
</form>