
<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">
        <div class="card-header-title">
            <i class="header-icon icofont-list icon-gradient bg-night-sky"> </i>
            Editar Menu
        </div>
    </div>

    <form id="save-menu" action="<?= $urls['save']['url'] ?>" method="<?= $urls['save']['method'] ?>">
        <input type="hidden" name="content">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" placeholder="Informe o Nome..."
                        class="form-control" value="<?= $name ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="slug">Chave</label>
                    <input type="text" id="meta" name="meta" placeholder="Informe uma Chave..."
                        class="form-control" value="<?= $meta ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-4">
                    <label for="utip_id">Tipo de Menu</label>
                    <select id="utip_id" name="utip_id" class="form-control">
                        <option value="">Selecionar...</option>
                        <?php 
                            foreach($userTypes as $type) {
                                $selected = $utip_id == $type->id ? 'selected' : '';
                                echo "<option value='{$type->id}' {$selected}>{$type->name_sing}</option>";
                            }
                        ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <div class="card-body">
            <div class="d-flex justify-content-around sticky-top pb-1" style="top: 64px; background-color: rgba(255, 255, 255, 0.5)">
                <button type="button" id="add-menu-item" class="btn btn-md btn-danger">Novo Ítem</button>
            </div>
            <ul class="list-group" id="menu-content"></ul>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <input type="submit" class="btn btn-lg btn-success" value="Salvar Menu">
            <a href="<?= $urls['return'] ?>" class="btn btn-lg btn-danger">Voltar</a>
        </div>
    </form>
</div>
<script>
    const menuData = <?php echo json_encode($content['items']) ?>;
</script>