<?php use Src\Views\Widgets\Forms\SaveUser as SaveUserForm; ?>
<section class="p-4 container-fluid">
    <div class="card shadow mb-4 br-15">
        <div class="card-header brt-15">    
            <h5>
                <i class="icofont-user text-info"> </i>
                <?= _('Account Information') ?>
                <span data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title='<?= _('Fill in the fields below in order to update your account data. Then, click on "Save".') ?>'>
                    <i class="icofont-question-circle" style="font-size: 1.1rem;"></i>
                </span>
            </h5>
        </div>

        <div class="card-body">
            <?php 
                $this->insert('widgets/forms/save-user', [
                    'widget' => new SaveUserForm(
                        id: $widget->getFormId(),
                        userTypes: $widget->getUserTypes(),
                        action: $router->route('editAccount.update'),
                        method: 'put',
                        user: $widget->getUser(),
                        isAccountEdit: true
                    )
                ]);
            ?>
        </div>

        <div class="card-footer d-block text-center brb-15">
            <button type="submit" form="<?= $widget->getFormId() ?>" class="btn btn-md btn-success"><?= _('Save') ?></button>
            <a href="<?= $router->route('user.index') ?>" class="btn btn-danger btn-md">
                <?= _('Return') ?>
            </a>
        </div>
    </div>
</section>