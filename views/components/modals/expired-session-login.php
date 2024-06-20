<div id="<?= $view->id ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Sessão expirada, faça login') ?></h5>
            </div>

            <div class="modal-body">
                <form id="login-form" action="<?= $view->action ?>" method="<?= $view->method ?>">
                    <div class="position-relative form-group">
                        <label for="email"><?= _('Email') ?></label>
                        <input type="text" class="form-control" name="email" placeholder="<?= _('Informe seu email...') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="position-relative form-group">
                        <label for="password"><?= _('Senha') ?></label>
                        <input type="password" class="form-control" name="password" placeholder="<?= _('Informe sua senha...') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>

            <div class="modal-footer d-block text-center">
                <button type="submit" form="login-form" class="btn btn-lg btn-success"><?= _('Entrar') ?></button>
                <a href="<?= $view->returnUrl ?>" class="btn btn-lg btn-secondary"><?= _('Voltar') ?></a>
            </div>
        </div>
    </div>
</div>

<script>
    LIBRARY.set('expiredSession', {
        modalId: <?= json_encode($view->id) ?>,
        formId: 'login-form',
        expiredUrl: <?= json_encode($view->expiredUrl) ?>
    });
</script>