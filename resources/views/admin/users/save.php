<?php 
    $lang = getLang()->setFilepath('views/admin/users/save')->getContent();
    $this->layout("themes/architect-ui/_theme", [
        'title' => $dbUser 
            ? $lang->get('edit.title', ['site_name' => SITE]) 
            : $lang->get('create.title', ['site_name' => SITE])
    ]);
?>

<?php $this->start('scripts'); ?>
<script> 
    const lang = <?php echo json_encode($lang->get('script')) ?>;
</script>
<script src="<?= url('resources/js/admin/users/save.js') ?>"></script>
<?php $this->end(); ?>

<?php 
    $this->insert('themes/architect-ui/components/title', [
        'title' => ($dbUser ? $lang->get('edit.title2', ['user_name' => $dbUser->name]) : $lang->get('create.title2')),
        'subtitle' => $dbUser ? $lang->get('edit.subtitle') : $lang->get('create.subtitle'),
        'icon' => 'pe-7s-user',
        'icon_color' => 'bg-malibu-beach'
    ]);
?>

<form action="<?= $dbUser ? $router->route('admin.users.update', ['user_id' => $dbUser->id]) : $router->route('admin.users.store') ?>" 
    method="<?= $dbUser ? 'put' : 'post' ?>" id="save-user">
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
                        class="form-control" value="<?= $dbUser ? $dbUser->name : '' ?>" maxlength="50">
                    <div class="invalid-feedback"></div>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="slug"><?= $lang->get('card1.save_user.slug.label') ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>

                        <input type="text" id="slug" name="slug" placeholder="<?= $lang->get('card1.save_user.slug.placeholder') ?>"
                            class="form-control" value="<?= $dbUser ? $dbUser->slug : '' ?>" maxlength="50">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email"><?= $lang->get('card1.save_user.email.label') ?></label>
                    <input type="email" id="email" name="email" placeholder="<?= $lang->get('card1.save_user.email.placeholder') ?>"
                        class="form-control" value="<?= $dbUser ? $dbUser->email : '' ?>" maxlength="100">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="utip_id"><?= $lang->get('card1.save_user.user_type.label') ?></label>
                    <select id="utip_id" name="utip_id" class="form-control">
                        <option value=""><?= $lang->get('card1.save_user.user_type.option0') ?></option>
                        <?php 
                            foreach($userTypes as $userType) {
                                $selected = $dbUser ? ($dbUser->utip_id == $userType->id ? 'selected' : '') : '';
                                echo "<option value='{$userType->id}' {$selected}>{$userType->name_sing}</option>";
                            }
                        ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <?php if($dbUser): ?>
            <div class="form-row">
                <div class="form-group col-md-12 align-middle">
                    <div class="d-flex">
                        <p class="mb-0 mr-2"><strong>Deseja alterar a Senha?</strong></p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" 
                                id="update_password1" value="1">
                            <label class="form-check-label" for="update_password1">
                                <?= $lang->get('card1.save_user.update_password.option1') ?>
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="update_password" 
                                id="update_password2" value="0" checked>
                            <label class="form-check-label" for="update_password2">
                                <?= $lang->get('card1.save_user.update_password.option2') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-row" id="password" style="<?= $dbUser ? 'display: none' : '' ?>">
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
            <input type="submit" class="btn btn-lg btn-success" 
                value="<?= $dbUser ? $lang->get('edit.submit') : $lang->get('create.submit') ?>">
            <a href="<?= $router->route('admin.users.index') ?>" class="btn btn-danger btn-lg">
                <?= $lang->get('card1.return') ?>
            </a>
        </div>
    </div>
</form>