<?php $this->insert('components/main-layout/header-languages', ['component' => $component->getLanguages()]); ?>

<?php if(!$component->isLogged()): ?>
<div class="text-end">
    <a href="<?= $router->route('auth.index') ?>" class="btn btn-outline-primary me-2">
        <?= _('Login') ?>
    </a>
</div>
<?php else: ?>

<?php 
    if($component->hasNotificationBell()) {
        $this->insert('components/main-layout/header-notification-bell', ['component' => $component->getNotificationBell()]);
    }
    $this->insert('components/main-layout/header-avatar-dropdown', ['component' => $component->getAvatarDropdown()]);
?>

<?php endif; ?>