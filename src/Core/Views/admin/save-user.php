<form id="save-user" action="<?= $urls['save']['url'] ?>" method="<?= $urls['save']['method'] ?>">
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
                    <input type="text" id="name" name="name" placeholder="Informe o Nome..."
                        class="form-control" value="<?= $name ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="slug">Apelido</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>

                        <input type="text" id="slug" name="slug" placeholder="Informe um Apelido..."
                            class="form-control" value="<?= $slug ?>" maxlength="100" 
                            data-action="<?= $urls['validate_slug'] ?>" data-method="POST">
                        <div id="slug-feedback" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Informe o E-mail..."
                        class="form-control" value="<?= $email ?>"  maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="utip_id">Nível do Usuário</label>
                    <select id="utip_id" name="utip_id" class="form-control">
                        <option value="">Selecionar...</option>
                        <?php 
                            foreach($userTypes as $type) {
                                $selected = $utip_id == $type->id ? 'selected' : '';
                                echo "<option value='{$type->id}' {$selected}>{$type->name_sing}</option>";
                            }
                        ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <?php if($id): ?>
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
            <?php endif ?>
            
            <div class="form-row" id="password" style="<?= $id ? 'display: none' : '' ?>">
                <div class="form-group col-md-6">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" 
                        placeholder="Informe a senha..." class="form-control">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="confirm_password">Confirmação de Senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                        placeholder="Confirme a senha..." class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <input type="submit" class="btn btn-lg btn-success" value="Salvar Usuário">
            <a href="<?= $urls['return'] ?>" class="btn btn-danger btn-lg">Voltar</a>
        </div>
    </div>
</form>