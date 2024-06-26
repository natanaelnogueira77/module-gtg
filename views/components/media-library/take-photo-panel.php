<div class="card">
    <div class="card-body">
        <div class="form-group mb-0">
            <label for="ml-camera-select"><?= _('Selecione um dispositivo...') ?></label>
            <select id="ml-camera-select" class="form-control"></select>
        </div>
    </div>
    <hr class="my-0">
    
    <div class="card-body">
        <div id="ml-current-camera-area">
            <h5 class="card-title"><?= _('Câmera Atual') ?></h5>
            <div class="d-flex justify-content-around">
                <video id="ml-video" class="border border-primary" style="max-width: 100%" muted autoplay playsinline></video>
            </div>
        </div>
    
        <div id="ml-captured-image-area" style="display: none;">
            <h5 class="card-title"><?= _('Imagem Capturada') ?></h5>
            <div class="d-flex justify-content-around">
                <canvas id="ml-canvas" class="border border-primary" width="640" height="480" style="max-width: 100%"></canvas>
            </div>
        </div>
    </div>

    <div class="card-footer d-block text-center">
        <button type="button" id="ml-snap" class="btn btn-lg btn-primary"><?= _('Capturar') ?></button>
        <button type="button" id="ml-save-snap" class="btn btn-lg btn-success" style="display: none;"><?= _('Salvar Captura') ?></button>
        <button type="button" id="ml-discard-snap" class="btn btn-lg btn-danger" style="display: none;"><?= _('Descartar') ?></button>
        <button type="button" id="ml-photo-cancel" class="btn btn-danger btn-lg"><?= _('Voltar') ?></button>
    </div>
</div>