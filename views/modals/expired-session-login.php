<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-hidden="true" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Expired Session, Login') ?></h5>
            </div>

            <div class="modal-body">
                <?php $this->insert('widgets/forms/expired-session-login', ['formId' => $formId]) ?>
            </div>

            <div class="modal-footer d-block text-center">
                <button type="submit" form="<?= $formId ?>" class="btn btn-success"><?= _('Login') ?></button>
            </div>
        </div>
    </div>
</div>