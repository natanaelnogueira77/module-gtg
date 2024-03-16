<div class="card">
    <div class="card-body">
        <label id="ml-upload-area" class="w-100" style="cursor: pointer;" for="ml-upload">
            <div id="ml-area" class="card card-border border-info w-100 p-5" style="cursor: pointer;">
                <div class="d-flex justify-content-around align-items-center" style="height: 90%;">
                    <div>
                        <h3 class="text-center"><?= _('Click to select or') ?></h3>
                        <h3 class="text-center"><?= _('drag the file(s)') ?></h3>
                        <h5 class="text-center" id="ml-maxsize"></h5>
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

    <div class="card-footer d-block text-center">
        <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal">
            <?= _('Return') ?>
        </button>
    </div>
</div>