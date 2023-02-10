<?php $lang = getLang()->setFilepath('views/components/expired-session')->getContent() ?>
<script>
    $(function () {
        const app = new App();
        app.expiredSession(<?php echo json_encode($check) ?>);
    });
</script>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="login-modal" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $lang->get('title') ?></h5>
            </div>
            <form id="login_form" action="<?= $action ?>" method="post">
                <div class="modal-body">
                    <div class="position-relative form-group">
                        <label for="email"><?= $lang->get('email.label') ?></label>
                        <input type="text" class="form-control" name="email" 
                            placeholder="<?= $lang->get('email.placeholder') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="position-relative form-group">
                        <label for="password"><?= $lang->get('password.label') ?></label>
                        <input type="password" class="form-control" placeholder="<?= $lang->get('password.placeholder') ?>" 
                            name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer d-block text-center">
                    <input type="submit" class="btn btn-lg btn-primary" value="<?= $lang->get('submit.value') ?>">
                    <a href="<?= $return ?>" class="btn btn-lg btn-secondary">
                        <?= $lang->get('return_button') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>