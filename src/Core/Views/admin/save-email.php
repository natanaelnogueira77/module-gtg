<form id="save-email" action="<?= $urls['save']['url'] ?>" method="<?= $urls['save']['method'] ?>">
    <div class="card shadow mb-4 br-15">
        <div class="card-header-tab card-header-tab-animation card-header brt-15">    
            <div class="card-header-title">
                <i class="header-icon icofont-mail icon-gradient bg-malibu-beach"> </i>
                Informações do E-mail
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" placeholder="Nome do E-mail..."
                        class="form-control" value="<?= $name ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="meta">Chave</label>
                    <input type="text" id="meta" name="meta" placeholder="Informe uma Chave..."
                        class="form-control" value="<?= $meta ?>" maxlength="100">
                    <div id="meta-feedback" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="subject">Assunto</label>
                    <input type="text" id="subject" name="subject" placeholder="Assunto do E-mail..."
                        class="form-control" value="<?= $subject ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <h5 class="card-title">Corpo do E-mail</h5>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="content">Corpo</label>
                    <textarea id="content" name="content" class="form-control" rows="10" 
                        placeholder="Digite HTML aqui..."><?= $content ?></textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="d-block text-center">
                <button type="button" id="email-preview" class="btn btn-lg btn-danger">Prévia</button>
            </div>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <input type="submit" class="btn btn-lg btn-success" value="Salvar">
            <a href="<?= $urls['return'] ?>" class="btn btn-danger btn-lg">Voltar</a>
        </div>
    </div>
</form>