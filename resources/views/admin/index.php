<?php 
    $lang = getLang()->setFilepath('views/admin/index')->getContent();
    $this->layout("themes/architect-ui/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE])
    ]);
?>

<?php $this->start('scripts'); ?>
<script> 
    const lang = <?php echo json_encode($lang->get('script')) ?>;
</script>
<script src="<?= url('resources/js/admin/index.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => $lang->get('title2'),
        'subtitle' => $lang->get('subtitle'),
        'icon' => 'pe-7s-home',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-3 br-15">
            <div class="card-header-tab card-header-tab-animation card-header brt-15">    
                <div class="card-header-title">
                    <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
                    <?= $lang->get('card1.title') ?>
                </div>
            </div>

            <div class="card-body">
                <div class="card-text"><?= $lang->get('card1.text1') ?> <strong><?= $gtgVersion ?></strong></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="main-card mb-3 card br-15">
            <?php if($userTypes): ?>
            <ul class="list-group list-group-flush">
                <?php foreach($userTypes as $userType): ?>
                <li class="list-group-item">
                    <div class="widget-content p-0">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper mb-2">
                                <div class="widget-content-left">
                                    <div class="widget-heading"><?= $userType->name_plur ?></div>
                                    <div class="widget-subheading">
                                        <?= $lang->get('card2.subheading', ['user_type' => $userType->name_plur]) ?>
                                    </div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">
                                        <?= $countUsers[$userType->id] ?? 0 ?>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content-wrapper">
                                <div class="widget-content-right">
                                    <button class="btn btn-lg btn-success" data-info="users" data-id="<?= $userType->id ?>">
                                        <?= $lang->get('card2.button', ['user_type' => $userType->name_plur]) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </div>
    </div>
</div>

<div id="panels_top"></div>

<div class="card shadow mb-4 panels br-15" id="panel_users" style="display: none;">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-investigator icon-gradient bg-night-sky"> </i>
            <?= $lang->get('card3.title') ?>
        </div>
    </div>
    
    <div class="card-body">
        <form id="filters">
            <?php $this->insert('components/data-table-filters', ['formId' => 'filters']); ?>
            <div class="form-row"> 
                <div class="form-group col-md-4 col-sm-6">
                    <label><?= $lang->get('card3.filters.user_type.label') ?></label>
                    <select name="user_type" class="form-control">
                        <option value=""><?= $lang->get('card3.filters.user_type.option0') ?></option>
                        <?php 
                            if($userTypes) {
                                foreach($userTypes as $userType) {
                                    echo "<option value=\"{$userType->id}\">{$userType->name_plur}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <?php $this->insert('components/data-table-buttons', ['formId' => 'filters', 'clearId' => 'clear']); ?>
        </form>
    </div>
    <hr class="my-0">
    <div class="card-body">
        <div id="users" data-action="<?= $router->route('admin.users.list') ?>"></div>
    </div>
</div>

<div class="card shadow mb-4 br-15">
    <div class="card-header-tab card-header-tab-animation card-header brt-15">    
        <div class="card-header-title">
            <i class="header-icon icofont-gear icon-gradient bg-night-sky"> </i>
            <?= $lang->get('card4.title') ?>
        </div>
    </div>

    <form id="system" action="<?= $router->route('admin.system') ?>" method="put">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="style"><?= $lang->get('card4.system.style.label') ?></label>
                    <select id="style" name="style" class="form-control">
                        <option value=""><?= $lang->get('card4.system.style.option0') ?></option>
                        <option value="light" <?= $configData['style'] == 'light' ? 'selected' : '' ?>>
                            <?= $lang->get('card4.system.style.option1') ?>
                        </option>
                        <option value="dark" <?= $configData['style'] == 'dark' ? 'selected' : '' ?>>
                            <?= $lang->get('card4.system.style.option2') ?>
                        </option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label><?= $lang->get('card4.system.login_img.label') ?></label>
                    <div class="d-flex justify-content-around">
                        <img id="login_img_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['login_img']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="login_img" name="login_img" value="<?= $configData['login_img'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="login_img_upload">
                            <i class="icofont-upload-alt"></i> <?= $lang->get('card4.system.login_img.button') ?>
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><?= $lang->get('card4.system.logo.label') ?></label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo" name="logo" value="<?= $configData['logo'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_upload">
                            <i class="icofont-upload-alt"></i> <?= $lang->get('card4.system.logo.button') ?>
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label><?= $lang->get('card4.system.logo_icon.label') ?></label>
                    <div class="d-flex justify-content-around">
                        <img id="logo_icon_view" style="max-height: 100px; max-width: 100%;" 
                            src="<?= url($configData['logo_icon']) ?>">
                    </div>

                    <div class="d-block text-center mt-2">
                        <input type="hidden" id="logo_icon" name="logo_icon" value="<?= $configData['logo_icon'] ?>">
                        <button type="button" class="btn btn-outline-primary btn-md btn-block" id="logo_icon_upload">
                            <i class="icofont-upload-alt"></i> <?= $lang->get('card4.system.logo_icon.button') ?>
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-around brb-15">
            <input type="submit" class="btn btn-md btn-success btn-block" 
                value="<?= $lang->get('card4.system.submit.value') ?>">
        </div>
    </form>
</div>