<?php

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\Notification;

class NotificationsController extends Controller
{
    public function markAllAsRead(Request $request): void 
    {
        if($notifications = Notification::getByUserId($this->session->getAuth()->id)) {
            foreach($notifications as $notification) $notification->setAsRead();
            Notification::saveMany($notifications);
        }

        $this->writeSuccessResponse();
    }

    public function getAllUnread(Request $request): void 
    {
        $notifications = Notification::getUnreadByUser($this->session->getAuth());
        $this->writeSuccessResponse([
            'data' => $notifications ? array_map(fn($notification) => array_replace($notification->columnsValuesToArray(), [
                'created_at' => $notification->getCreatedAtDateTime()->format('d/m/Y - H:i')
            ]), $notifications) : null
        ]);
    }
}