<div id="save-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"  
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Cadastrar Usuário') ?></h5>
            </div>
            
            <div class="modal-body">
                <?php $this->insert('widgets/forms/save-user', ['userTypes' => $userTypes]) ?>
            </div>
            
            <div class="modal-footer d-block text-center">
                <button type="submit" form="save-user" class="btn btn-success btn-lg"><?= _('Salvar') ?></button>
                <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal"><?= _('Voltar') ?></button>
            </div>
        </div>
    </div>
</div>