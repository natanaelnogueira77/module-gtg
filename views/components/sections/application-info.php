<section id="<?= $view->id ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3 br-15">
                <div class="card-header-tab card-header-tab-animation card-header brt-15">    
                    <div class="card-header-title">
                        <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
                        <?= _('Informações da Aplicação') ?>
                    </div>
                </div>
    
                <div class="card-body">
                    <div class="card-text"><?= sprintf(_('Versão: <strong>%s</strong>'), $view->version) ?></div>
                </div>
    
                <div class="card-footer d-block text-center brb-15">
                    <?= $view->getActionButtons() ?>
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="main-card mb-3 card br-15">
                <?php if($view->userTypes): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach($view->userTypes as $utId => $userType): ?>
                    <li class="list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper mb-2">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"><?= $userType ?></div>
                                        <div class="widget-subheading">
                                            <?= sprintf(_("Usuários com permissão de %s"), $userType) ?>
                                        </div>
                                    </div>
    
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-success">
                                            <?= $view->usersCount[$utId] ?? 0 ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>