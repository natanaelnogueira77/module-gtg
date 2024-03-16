<div class="dropdown text-end me-2">
    <a id="bell-notifications" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
        class="d-block text-decoration-none" style="cursor: pointer;">
        <?php if($component->hasNotifications()): ?>
        <div id="notifications-number" class="badge badge-pill bg-danger position-absolute p-1 ml-0">
            <?= $component->getNotificationsCount() ?>
        </div>
        <?php endif; ?>
        <i class="icofont-alarm <?= $component->getIconColor() ?>" style="font-size: 2.4rem;"></i>
    </a>

    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
        <h6 tabindex="-1" class="dropdown-header"><?= _('Notifications') ?></h6>
        <?php foreach($component->getNotifications() as $notification): ?>
        <div tabindex="-1" class="dropdown-divider my-0 <?= $notification->wasRead() ? 'bg-light' : '' ?>"></div>
        <p class="px-3 py-2 mb-0 <?= $notification->wasRead() ? 'bg-light' : '' ?>">
            <?= $notification->getContent() ?>
        </p>
        <?php endforeach; ?>
    </div>
</div>