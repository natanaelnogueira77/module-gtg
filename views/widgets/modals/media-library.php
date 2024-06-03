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

                <?php if($session->getAuth()): ?>
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-3" class="nav-link">
                        <?= _('Biblioteca de Mídia') ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="ml-tab-1" role="tabpanel">
                    <?php $this->insert('widgets/media-library/upload-label-panel') ?>
                </div>
                
                <div class="tab-pane tabs-animation fade show" id="ml-tab-2" role="tabpanel">
                    <?php $this->insert('widgets/media-library/take-photo-panel') ?>
                </div>

                <?php if($session->getAuth()): ?>
                <div class="tab-pane tabs-animation fade show" id="ml-tab-3" role="tabpanel">
                    <?php $this->insert('widgets/media-library/file-list-panel') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>