<div id="media-library-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Biblioteca de Mídia') ?></h5>
            </div>

            <ul class="nav nav-tabs mb-0">
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-1" class="nav-link show active">
                        <?= _('Upload') ?>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-2" class="nav-link">
                        <?= _('Tirar Foto') ?>
                    </a>
                </li>

                <?php if($view->hasSession): ?>
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-3" class="nav-link">
                        <?= _('Biblioteca de Mídia') ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="ml-tab-1" role="tabpanel">
                    <?= $view->getUploadLabelPanel() ?>
                </div>
                
                <div class="tab-pane tabs-animation fade show" id="ml-tab-2" role="tabpanel">
                    <?= $view->getTakePhotoPanel() ?>
                </div>

                <?php if($view->hasSession): ?>
                <div class="tab-pane tabs-animation fade show" id="ml-tab-3" role="tabpanel">
                    <?= $view->getFileListPanel() ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    LIBRARY.set('mediaLibrary', {
        addFileUrl: <?= json_encode($view->addFileUrl) ?>,
        removeFileUrl: <?= json_encode($view->removeFileUrl) ?>,
        loadFilesUrl: <?= json_encode($view->loadFilesUrl) ?>,
        errorMessages: <?= json_encode([
            'allowed_extensions' => _('A extensão deste arquivo não é permitida aqui! Extensões permitidas: '),
            'size_limit' => sprintf(_('O arquivo que você tentou enviar é maior do que %sMB!'), '{size}'),
            'failed_to_read' => _("Lamentamos, mas ocorreu um erro ao ler o arquivo!")
        ]) ?>
    });
</script>