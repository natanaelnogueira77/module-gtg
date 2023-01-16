<div class="d-flex justify-content-between">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Linhas</span>
            </div>
            <select form="<?= $formId ?>" name="limit" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Buscar</span>
            </div>
            <input form="<?= $formId ?>" type="search" name="search" 
                placeholder="Buscar por" class="form-control">
        </div>
    </div>
</div>