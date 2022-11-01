<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-3 br-15">
            <div class="card-header-tab card-header-tab-animation card-header brt-15">    
                <div class="card-header-title">
                    <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
                    Módulo GTG
                </div>
            </div>

            <div class="card-body">
                <div class="card-text">Versão: <strong><?= $gtgVersion ?></strong></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="main-card mb-3 card br-15">
            <?php if($userTypes): ?>
            <ul class="list-group list-group-flush">
                <?php foreach($userTypes as $userType): ?>
                <li class="list-group-item">
                    <div class="widget-content p-0">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper mb-2">
                                <div class="widget-content-left">
                                    <div class="widget-heading"><?= $userType->name_plur ?></div>
                                    <div class="widget-subheading"><?= $userType->name_plur ?> do Sistema</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">
                                        <?= $countUsers[$userType->id] ?? 0 ?>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content-wrapper">
                                <div class="widget-content-right">
                                    <button class="btn btn-lg btn-success" data-info="users" data-id="<?= $userType->id ?>">
                                        Ver <?= $userTypes[$userType->id]->name_plur ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </div>
    </div>
</div>

<div id="panels_top"></div>

<div class="card shadow mb-4 panels br-15" id="panel_users" style="display: none;">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-investigator icon-gradient bg-night-sky"> </i>
            Usuários
        </div>
    </div>

    <div class="card-body" id="usuarios" 
        data-table-id="users_1" 
        data-action="<?= $urls['table_users'] ?>"></div>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
            Configurações do Sistema
        </div>
    </div>
    <form id="system" action="<?= $urls['system_config']['url'] ?>" method="<?= $urls['system_config']['method'] ?>">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Título do Sistema <span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Informe o título do Sistema..."
                        class="form-control" value="<?= $configData['title'] ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="style">Tema <span class="text-danger">*</span></label>
                    <select id="style" name="style" class="form-control">
                        <option value="">Escolha o Tema de Cores do Sistema...</option>
                        <option value="light" <?= $configData['style'] == 'light' ? 'selected' : '' ?>>Tema Claro</option>
                        <option value="dark" <?= $configData['style'] == 'dark' ? 'selected' : '' ?>>Tema Escuro</option>
                        <option value="colour" <?= $configData['style'] == 'colour' ? 'selected' : '' ?>>Tema Colorido</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Logo</label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo" name="logo" value="<?= $configData['logo'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_upload">
                            <i class="icofont-upload-alt"></i> Subir Arquivo
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group col-md-6">
                    <label>Ícone (Tamanho Recomendado: 512 x 512)</label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_icon_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo_icon']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo_icon" name="logo_icon" value="<?= $configData['logo_icon'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_icon_upload">
                            <i class="icofont-upload-alt"></i> Subir Arquivo
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="sitename">Nome do Site <span class="text-danger">*</span></label>
                    <input type="text" name="sitename" placeholder="Informe o Nome do Site..."
                        class="form-control" value="<?= $mainData['sitename'] ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="error_mail">E-mail de Erros <span class="text-danger">*</span></label>
                    <input type="email" name="error_mail" placeholder="E-mail que receberá os erros do sistema..."
                        class="form-control" value="<?= $mainData['error_mail'] ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="root">URL Raiz <span class="text-danger">*</span></label>
                    <input type="url" name="root" placeholder="Informe a URL Raiz do site..."
                        class="form-control" value="<?= $mainData['root'] ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-3">
                    <label for="sessname">Chave de Sessão <span class="text-danger">*</span></label>
                    <input type="text" name="sessname" placeholder="Determine a Chave de Sessão de Usuário..."
                        class="form-control" value="<?= $mainData['sessname'] ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-3">
                    <label for="version">Versão do Sistema <span class="text-danger">*</span></label>
                    <input type="text" name="version" placeholder="Determine a Versão do Sistema..."
                        class="form-control" value="<?= $mainData['version'] ?>" maxlength="10">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-around brb-15">
            <button class="btn btn-md btn-success btn-block">Salvar Configurações</button>
        </div>
    </form>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-users icon-gradient bg-malibu-beach"> </i>
            Nomenclaturas dos Usuários
        </div>
    </div>
    <form id="usertypes" action="<?= $urls['usertypes_config']['url'] ?>" 
        method="<?= $urls['usertypes_config']['method'] ?>">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <th class="text-center align-middle">Nível de Usuário</th>
                    <th class="text-center align-middle">Singular <span class="text-danger">*</span></th>
                    <th class="text-center align-middle">Plural <span class="text-danger">*</span></th>
                </thead>
                <tbody>
                    <?php 
                    if($userTypes):
                        foreach($userTypes as $userType): 
                        ?>
                        <input type="hidden" name="utip_id[<?= $userType->id ?>]" value="<?= $userType->id ?>">
                        <tr>
                            <td class="align-middle"><?= $userType->name_sing ?></td>
                            <td class="align-middle">
                                <input type="text" id="name_sing<?= $userType->id ?>" name="name_sing[<?= $userType->id ?>]" 
                                    placeholder="Singular..." class="form-control" value="<?= $userType->name_sing ?>" 
                                    maxlength="100">
                                <div class="invalid-feedback"></div>
                            </td>
                            <td class="align-middle">
                                <input type="text" id="name_plur<?= $userType->id ?>" name="name_plur[<?= $userType->id ?>]" 
                                    placeholder="Plural..." class="form-control" 
                                    value="<?= $userType->name_plur ?>" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </td>
                        </tr>
                        <?php 
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-around brb-15">
            <button class="btn btn-md btn-success btn-block">Salvar Configurações</button>
        </div>
    </form>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-mail icon-gradient bg-love-kiss"> </i>
            Configurações de E-mail
        </div>
    </div>
    <form id="email" action="<?= $urls['email_config']['url'] ?>" method="<?= $urls['email_config']['method'] ?>">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Host <span class="text-danger">*</span></label>
                    <input type="text" name="host" placeholder="Informe o Host..."
                        class="form-control" value="<?= $emailData['host'] ?>">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Porta <span class="text-danger">*</span></label>
                    <input type="text" name="port" placeholder="Informe a Porta..."
                        class="form-control" value="<?= $emailData['port'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nome de Usuário <span class="text-danger">*</span></label>
                    <input type="text" name="username" placeholder="Informe o Nome de Usuário..."
                        class="form-control" value="<?= $emailData['username'] ?>">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Senha <span class="text-danger">*</span></label>
                    <input type="password" name="password" placeholder="Informe a Senha..."
                        class="form-control" value="<?= $emailData['password'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nome <span class="text-danger">*</span></label>
                    <input type="text" name="name" placeholder="Informe o Nome do Remetente..."
                        class="form-control" value="<?= $emailData['name'] ?>">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>E-mail <span class="text-danger">*</span></label>
                    <input type="text" name="email" placeholder="Informe o E-mail do remetente..."
                        class="form-control" value="<?= $emailData['email'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-around brb-15">
            <button class="btn btn-md btn-success btn-block">Salvar Configurações</button>
        </div>
    </form>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-mail icon-gradient bg-love-kiss"> </i>
            Configurações de Banco de Dados
        </div>
    </div>
    <form id="database" action="<?= $urls['db_config']['url'] ?>" method="<?= $urls['db_config']['method'] ?>">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Driver <span class="text-danger">*</span></label>
                    <select name="driver" class="form-control">
                        <option value="">Selecionar...</option>
                        <option value="mysql" <?= $dbData['driver'] == 'mysql' ? 'selected' : '' ?>>MySQL</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Database <span class="text-danger">*</span></label>
                    <input type="text" name="dbname" placeholder="Nome do Esquema..."
                        class="form-control" value="<?= $dbData['dbname'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Host <span class="text-danger">*</span></label>
                    <input type="text" name="host" placeholder="Informe o Host..."
                        class="form-control" value="<?= $dbData['host'] ?>">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Porta <span class="text-danger">*</span></label>
                    <input type="text" name="port" placeholder="Informe a Porta..."
                        class="form-control" value="<?= $dbData['port'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nome de Usuário <span class="text-danger">*</span></label>
                    <input type="text" name="username" placeholder="Informe o Nome de Usuário..."
                        class="form-control" value="<?= $dbData['username'] ?>">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label>Senha <span class="text-danger">*</span></label>
                    <input type="password" name="passwd" placeholder="Informe a Senha..."
                        class="form-control" value="<?= $dbData['passwd'] ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-around brb-15">
            <button class="btn btn-md btn-success btn-block">Salvar Configurações</button>
        </div>
    </form>
</div>