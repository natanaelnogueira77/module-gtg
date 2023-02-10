<?php $lang = getLang()->setFilepath('views/components/media-library')->getContent() ?>
<div class="modal fade" id="modal-media-library" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" 
    data-load="<?= $mlLoad ?>" data-add="<?= $mlAdd ?>" data-delete="<?= $mlDelete ?>" data-path="<?= $path ?>" data-root="<?= $storeAt ?>">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $lang->get('title') ?></h5>
            </div>
            <div class="modal-body">
                <div class="card">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a data-toggle="tab" href="#ml-tab-1" class="nav-link show active">
                                <?= $lang->get('file_uploads') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#ml-tab-2" class="nav-link">
                                <?= $lang->get('media_library') ?>
                            </a>
                        </li>
                    </ul>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" style="height: 500px;" id="ml-tab-1" role="tabpanel">
                                <label id="ml-upload-area" style="cursor: pointer; height: 100%; width: 100%;" for="ml-upload">
                                    <div id="ml-area" class="card card-border border-info" 
                                        style="cursor: pointer; height: 100%; width: 100%;">
                                        <div class="d-flex justify-content-around align-items-center" style="height: 100%">
                                            <div>
                                                <h3 class="text-center"><?= $lang->get('click_select') ?></h3>
                                                <h3 class="text-center"><?= $lang->get('drag_files') ?></h3>
                                                <h5 class="text-center" data-text="<?= $lang->get('max_size') ?>" id="ml-maxsize"></h5>
                                                <div class="progress-bar-sm progress-bar-animated-alt progress">
                                                    <div class="progress-bar progress-bar-animated progress-bar-striped bg-primary" 
                                                        id="ml-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" 
                                                        aria-valuemax="100" style="width: 0%;"></div>
                                                </div>
                                                <h5 class="text-center" id="ml-progress"></h5>
                                            </div>
                                        </div>
                                        <input type="file" id="ml-upload" style="position: absolute; left: 0; top: 0; right: 0; 
                                            bottom: 0; width: 100%; opacity: 0; -webkit-appearance: none; cursor: pointer;">
                                    </div>
                                </label>
                            </div>
                            <div class="tab-pane tabs-animation fade show" id="ml-tab-2" role="tabpanel">
                                <form id="ml-images-list" class="mb-4">
                                    <div class="input-group">
                                        <input type="search" name="search" id="ml-search" class="form-control rounded" 
                                            placeholder="<?= $lang->get('search') ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="icofont-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-around" id="ml-pagination">
                                    <nav>
                                        <ul class="pagination"></ul>
                                    </nav>
                                </div>
                                <div class="w-100 d-flex flex-wrap" id="ml-list-group" style="height: 100%;"></div>
                                <div class="d-block text-center mt-4">
                                    <input type="hidden" id="ml-choosen-file">
                                    <button type="button" id="ml-choose" class="btn btn-primary" 
                                        data-allowed="<?= $lang->get('allowed_extensions') ?>">
                                        <?= $lang->get('choose') ?>
                                    </button>
                                    <button type="button" id="ml-cancel" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <?= $lang->get('cancel') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>