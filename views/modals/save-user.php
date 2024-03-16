<?php use Src\Views\Widgets\Forms\SaveUser as SaveUserForm; ?>
<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-hidden="true" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Save User') ?></h5>
            </div>

            <div class="modal-body">
                <?php $this->insert('widgets/forms/save-user', [
                    'widget' => new SaveUserForm(
                        id: $formId,
                        userTypes: $userTypes
                    )
                ]) ?>
            </div>

            <div class="modal-footer d-block text-center">
                <button type="submit" form="<?= $formId ?>" class="btn btn-success"><?= _('Save') ?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= _('Close') ?></button>
            </div>
        </div>
    </div>
</div>