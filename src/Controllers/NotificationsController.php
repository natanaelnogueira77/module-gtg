<?php

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\Notification;

class NotificationsController extends Controller
{
    public function markAllAsRead(Request $request): void 
    {
        if($dbNotifications = Notification::getByUserId($this->session->getAuth()->id)) {
            foreach($dbNotifications as $dbNotification) {
                $dbNotification->setAsRead();
            }

            Notification::saveMany($dbNotifications);
        }

        $this->writeSuccessResponse();
    }
}