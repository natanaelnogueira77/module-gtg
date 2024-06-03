<div class="app-header-right">
    <?php 
        $this->insert('widgets/layouts/dashboard/header-languages', $languages ?? []); 
        if($notificationsBell) {
            $this->insert('widgets/layouts/dashboard/header-notification-bell', $notificationsBell);
        }
        $this->insert('widgets/layouts/dashboard/header-avatar-dropdown', $avatar ?? []);
    ?>
</div>