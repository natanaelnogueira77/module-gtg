<div class="card">
    <div class="card-body">
        <form id="ml-images-list">
            <div class="input-group">
                <input type="search" name="search" id="ml-search" class="form-control rounded" 
                    placeholder="<?= _('Search...') ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="icofont-search"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" id="ml-clean-search">
                    <i class="icofont-close"></i>
                </button>
            </div>
        </form>
    </div>
    
    <hr class="my-0">
    
    <div class="card-body">
        <div class="d-flex justify-content-around" id="ml-pagination">
            <nav>
                <ul class="pagination"></ul>
            </nav>
        </div>
    
        <div class="w-100 d-flex flex-wrap" id="ml-list-group" style="height: 100%;"></div>
    </div>

    <div class="card-footer d-block text-center">
        <input type="hidden" id="ml-choosen-file">
        <button type="button" id="ml-choose" class="btn btn-primary btn-lg">
            <?= _('Choose') ?>
        </button>
        
        <button type="button" id="ml-cancel" class="btn btn-danger btn-lg" data-bs-dismiss="modal">
            <?= _('Return') ?>
        </button>
    </div>
</div>