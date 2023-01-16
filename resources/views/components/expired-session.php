<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="login-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sessão Expirada, Faça Login</h5>
            </div>
            <form id="login_form" action="<?= $router->route('login.check') ?>" method="post">
                <div class="modal-body">
                    <div class="position-relative form-group">
                        <label for="login_email">Email</label>
                        <input type="text" class="form-control" name="login_email"
                            id="login_email" placeholder="Informe seu Email..." required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="position-relative form-group">
                        <label for="login_senha">Senha</label>
                        <input type="password" class="form-control" id="login_senha" 
                            placeholder="Informe sua senha..." name="login_senha" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer d-block text-center">
                    <input type="submit" class="btn btn-lg btn-primary" value="Entrar">
                    <a href="<?= $router->route('login.index') ?>" class="btn btn-lg btn-secondary">
                        Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>