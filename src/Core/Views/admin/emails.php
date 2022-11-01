<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-mail icon-gradient bg-primary"> </i>
            Templates de E-mail
        </div>

        <div class="btn-actions-pane-right">
            <div role="group" class="btn-group-sm btn-group">
                <a class="btn btn-lg btn-primary" href="<?= $urls['creation'] ?>">
                    Criar Template
                </a>
            </div>
        </div>
    </div>

    <div class="card-body" id="emails" 
        data-table-id="emails_1" 
        data-action="<?= $urls['table_action'] ?>">
    </div>
</div>