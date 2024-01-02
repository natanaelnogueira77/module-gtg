<?php

namespace Src\API\Controllers\User;

use GTG\MVC\Controller;
use Src\Models\Notification;
use Src\Utils\ErrorMessages;

class NotificationsController extends Controller 
{
    public function markAllAsRead(array $data): void 
    {   
        $user = $this->session->getAuth();

        if($dbNotifications = $user->notifications(['was_read' => 0])) {
            foreach($dbNotifications as $dbNotification) {
                $dbNotification->setAsRead();
            }

            if(!Notification::saveMany($dbNotifications)) {
                $this->setMessage('error', ErrorMessages::requisition())->APIResponse([], 500);
                return;
            }
        }

        $this->setMessage(
            'success', 
            _('Todas as notificações foram marcadas como "Lidas".')
        )->APIResponse([], 200);
    }
}