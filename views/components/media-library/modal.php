<div class="modal fade" id="media-library-modal" tabindex="-1" role="dialog" aria-hidden="true" 
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= _('Media Library') ?></h5>
            </div>

            <ul class="nav nav-tabs mb-0">
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-1" class="nav-link show active">
                        <?= _('Upload') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-2" class="nav-link">
                        <?= _('Take Photo') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="tab" href="#ml-tab-3" class="nav-link">
                        <?= _('Media Library') ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="ml-tab-1" role="tabpanel">
                    <?php $this->insert('components/media-library/upload-label'); ?>
                </div>

                <div class="tab-pane tabs-animation fade show" id="ml-tab-2" role="tabpanel">
                    <?php $this->insert('components/media-library/take-photo') ?>
                </div>

                <div class="tab-pane tabs-animation fade show" id="ml-tab-3" role="tabpanel">
                    <?php $this->insert('components/media-library/file-list') ?>
                </div>
            </div>
        </div>
    </div>
</div>