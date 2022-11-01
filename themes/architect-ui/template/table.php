<div class="d-flex justify-content-between">
    <?php if(!$table["no_lines"]): ?>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Linhas</span>
            </div>
            <select data-filter="limit" class="form-control">
                <option value="10" <?= $table["limit"] == 10 ? "selected" : "" ?>>10</option>
                <option value="25" <?= $table["limit"] == 25 ? "selected" : "" ?>>25</option>
                <option value="50" <?= $table["limit"] == 50 ? "selected" : "" ?>>50</option>
                <option value="100" <?= $table["limit"] == 100 ? "selected" : "" ?>>100</option>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!$table["no_search"]): ?>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Buscar</span>
            </div>
            <input type="search" data-filter="s" placeholder="Buscar por..." 
                class="form-control" value="<?= $table["search"] ?>">
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="form-row"> 
    <?php 
    if($table["filters"]):
        foreach($table['filters'] as $filter):
        ?>
        <div class="form-group col-md-4 col-sm-6">
            <label><?= $filter["label"] ?></label>
            <?php if($filter["type"] == "select"): ?>
            <select data-filter="<?= $filter["key"] ?>" class="form-control">
                <?php foreach($filter["options"] as $option): ?>
                <option value="<?= $option["value"] ?>" <?= $option["selected"] ? "selected" : "" ?>>
                    <?= $option["text"] ?>
                </option>
                <?php endforeach ?>
            </select>
            <?php elseif(in_array($filter["type"], ["text", "date", "number", "time", "email"])): ?>
            <input type="<?= $filter["type"] ?>" data-filter="<?= $filter["key"] ?>" class="form-control" value="<?= $filter["value"] ?>">
            <?php elseif(in_array($filter["type"], ["multiple"])): ?>
            <select data-filter="<?= $filter["key"] ?>" class="form-control" multiple>
                <?php foreach($filter["options"] as $option): ?>
                <option value="<?= $option["value"] ?>" <?= $option["selected"] ? "selected" : "" ?>>
                    <?= $option["text"] ?>
                </option>
                <?php endforeach ?>
            </select>
            <?php endif; ?>
        </div>
        <?php 
        endforeach;
    endif;
    ?>
</div>

<?php if(!$table["no_buttons"]): ?>
<div class="d-block text-center mb-2">
    <button type="button" data-button="filter" class="btn btn-outline-primary btn-lg btn-icon">
        <i class="icofont-search"></i> Filtrar
    </button>
    <button type="button" data-button="clear" class="btn btn-outline-danger btn-lg btn-icon">
        <i class="icofont-close"></i> Limpar
    </button>
</div>
<?php endif; ?>

<?php if($table["last_page"] > 1): ?>
<div class="d-flex justify-content-around">
    <nav>
        <ul class="pagination">
            <?php if($table["page"] - 1  > 0): ?>
            <li class="page-item">
                <button class="page-link" aria-label="Anterior" data-page="<?= $table["page"] - 1 ?>">
                    <span aria-hidden="true">«</span><span class="sr-only">Anterior</span>
                </button>
            </li>
            <?php endif ?>

            <?php 
            if($table["page"] >= 6): 
                for($i = $table["page"] - 4; $i <= $table["last_page"] && $i <= $table["page"] + 5; $i++):
                ?>
                <li class="page-item <?= $table["page"] == $i ? "active" : "" ?>">
                    <button class="page-link" aria-label="Página <?= $i ?>" data-page="<?= $i ?>">
                        <span aria-hidden="true"><?= $i ?></span><span class="sr-only">Página <?= $i ?></span>
                    </button>
                </li>
                <?php 
                endfor;
            else: 
                for($i = 1; $i <= $table["last_page"] && $i <= 10; $i++):
                ?>
                <li class="page-item <?= $table["page"] == $i ? "active" : "" ?>">
                    <button class="page-link" aria-label="Página <?= $i ?>" data-page="<?= $i ?>">
                        <span aria-hidden="true"><?= $i ?></span><span class="sr-only">Página <?= $i ?></span>
                    </button>
                </li>
                <?php 
                endfor;
            endif;
            ?>

            <?php if($table["page"] < $table["last_page"]): ?>
            <li class="page-item">
                <button class="page-link" aria-label="Próxima" data-page="<?= $table["page"] + 1 ?>">
                    <span aria-hidden="true">»</span><span class="sr-only">Próxima</span>
                </button>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<div class="table-responsive-lg" style="overflow-y: visible;">
    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
        <thead>
            <?php 
            if($table["headers"]):
                foreach($table["headers"] as $head): 
                ?>
                <th class="align-middle text-white <?= $head["selected"] ? "bg-primary" : "bg-info" ?>">
                    <?php if($head["sort"]): ?>
                    <div class="d-flex justify-content-between align-items-center" style="cursor: pointer;" 
                        data-ord="<?= $head["key"] ?>" data-ordtype="<?= $head["order"] ?>">
                        <p class="mb-0"><?= $head["name"] ?></p>
                        <div class="d-flex flex-column">
                            <i class="icofont-arrow-up text-<?= $head["order"] == "ASC" ? "secondary" : "light" ?>"></i>
                            <i class="icofont-arrow-down text-<?= $head["order"] == "DESC" ? "secondary" : "light" ?>"></i>
                        </div>
                    </div>
                    <?php else: ?>
                    <?= $head["name"] ?>
                    <?php endif ?>
                </th>
                <?php 
                endforeach;
            endif;
            ?>
        </thead>
        <?php if($table["data"]): ?>
        <tbody>
            <?php foreach($table["data"] as $row): ?>
            <tr>
                <?php foreach($table["headers"] as $head): ?>
                <td class="align-middle"><?= $row[$head["key"]] ?></td>
                <?php endforeach ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <?php endif; ?>
    </table>
    
    <?php if(!$table["data"]): ?>
    <?= 
        $table["no_data_msg"] 
        ? $table["no_data_msg"] 
        : "<h5 class=\"text-center\">Nenhum resultado foi encontrado!</h5>"; 
    ?>
    <?php endif; ?>
</div>