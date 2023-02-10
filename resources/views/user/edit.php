<?php 
    $lang = getLang()->setFilepath('views/user/edit')->getContent();
    $this->layout("themes/architect-ui/_theme", [
        'title' => $lang->get('title', ['site_name' => SITE])
    ]);
?>

<?php $this->start('scripts'); ?>
<script> 
    const lang = <?php echo json_encode($lang->get('script')) ?>;
</script>
<script src="<?= url('resources/js/user/edit.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => $lang->get('title2'),
        'subtitle' => $lang->get('subtitle'),
        'icon' => 'pe-7s-user',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

<form id="save-user" action="<?= $router->route('user.edit.update') ?>" method="put">
    <div class="card shadow mb-4 br-15">
        <div class="card-header-tab card-header-tab-animation card-header brt-15">    
            <div class="card-header-title">
                <i class="header-icon icofont-user icon-gradient bg-malibu-beach"> </i>
                <?= $lang->get('card1.title') ?>
            </div>
        </div>

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name"><?= $lang->get('card1.save_user.name.label') ?></label>
                    <input type="text" id="name" name="name" placeholder="<?= $lang->get('card1.save_user.name.placeholder') ?>"
                        class="form-control" value="<?= $user->name ?>" maxlength="50">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="slug"><?= $lang->get('card1.save_user.slug.label') ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" id="slug" name="slug" placeholder="<?= $lang->get('card1.save_user.slug.placeholder') ?>"
                            class="form-control" value="<?= $user->slug ?>" maxlength="50">
                        <div id="slug-feedback" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="email"><?= $lang->get('card1.save_user.email.label') ?></label>
                    <input type="email" id="email" name="email" placeholder="<?= $lang->get('card1.save_user.email.placeholder') ?>"
                        class="form-control" value="<?= $user->email ?>"  maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12 align-middle">
                    <div class="d-flex">
                        <p class="mb-0 mr-2"><strong><?= $lang->get('card1.save_user.update_password.label') ?></strong></p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" id="update_password1" value="1">
                            <label class="form-check-label" for="update_password1">
                                <?= $lang->get('card1.save_user.update_password.option1') ?>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" id="update_password2" value="0" checked>
                            <label class="form-check-label" for="update_password2">
                                <?= $lang->get('card1.save_user.update_password.option2') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row" id="password" style="display: none;">
                <div class="form-group col-md-6">
                    <label for="password"><?= $lang->get('card1.save_user.password.label') ?></label>
                    <input type="password" id="password" name="password" 
                        placeholder="<?= $lang->get('card1.save_user.password.placeholder') ?>" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="confirm_password"><?= $lang->get('card1.save_user.confirm_password.label') ?></label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                        placeholder="<?= $lang->get('card1.save_user.confirm_password.placeholder') ?>" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <input type="submit" class="btn btn-lg btn-success" value="<?= $lang->get('card1.save_user.submit.value') ?>">
            <a href="<?= $router->route('user.index') ?>" class="btn btn-danger btn-lg">
                <?= $lang->get('card1.return') ?>
            </a>
        </div>
    </div>
</form>