<script>
    $(function () {
        const app = new App();

        const login_modal = $("#login-modal");
        const login_form = $("#login-form");

        var is_session_expired = false;

        function check_session(url) {
            app.callAjax({
                url: url,
                type: "post",
                data: {},
                success: function (response) {
                    if(response.success) {
                        login_modal.modal("show");
                        is_session_expired = true;
                    }
                },
                noLoad: true
            });
        }
        
        var count_interval = setInterval(function () {
            check_session(<?php echo json_encode($check) ?>);
            if(is_session_expired == false) {
                clearInterval(count_interval);
            }
        }, 10000);

        app.form(login_form, function (response) {
            login_modal.modal('toggle');
        });
    });
</script>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="login-modal" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Sessão expirada, faça login') ?></h5>
            </div>
            <form id="login-form" action="<?= $action ?>" method="post">
                <div class="modal-body">
                    <div class="position-relative form-group">
                        <label for="email"><?= _('Email') ?></label>
                        <input type="text" class="form-control" name="email" 
                            placeholder="<?= _('Informe seu email...') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="position-relative form-group">
                        <label for="password"><?= _('Senha') ?></label>
                        <input type="password" class="form-control" placeholder="<?= _('Informe sua senha...') ?>" 
                            name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer d-block text-center">
                    <input type="submit" class="btn btn-lg btn-primary" value="<?= _('Entrar') ?>">
                    <a href="<?= $return ?>" class="btn btn-lg btn-secondary">
                        <?= _('Voltar') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>