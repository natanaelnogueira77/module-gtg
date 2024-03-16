<section class="p-4 container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3 br-15">
                <div class="card-header brt-15">
                    <h5>
                        <i class="icofont-gear text-primary"> </i>
                        <?= _('Application Informations') ?>
                    </h5>
                </div>
    
                <div class="card-body">
                    <div class="card-text">
                        <?= sprintf(_('Name: <strong>%s</strong>'), $widget->getApplicationName()) ?>
                    </div>

                    <div class="card-text">
                        <?= sprintf(_('Version: <strong>%s</strong>'), $widget->getApplicationVersion()) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <ul class="list-group list-group-flush">
                    <?php foreach($widget->getUserTypes() as $userTypeId => $userType): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="mb-0"><?= $userType ?></h5>
                                <div class="opacity-7"><?= sprintf(_("Users with \"%s\" permission"), $userType) ?></div>
                            </div>

                            <h1 class="text-success">
                                <?= $widget->getUsersCount()[$userTypeId] ?? 0 ?>
                            </h1>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>