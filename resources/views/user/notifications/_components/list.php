<?php 
    if($dbNotifications):
        foreach($dbNotifications as $dbNotification):
        ?>
        <div tabindex="-1" class="dropdown-divider my-0 <?= $dbNotification->wasRead() ? 'bg-light' : '' ?>"></div>
        <p class="px-3 py-2 mb-0 <?= $dbNotification->wasRead() ? 'bg-light' : '' ?>">
            <?= $dbNotification->getContent() ?>
        </p>
        <?php 
        endforeach;
    endif;
?>