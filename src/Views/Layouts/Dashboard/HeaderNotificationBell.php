<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class HeaderNotificationBell extends Widget
{
    public function __construct(
        public readonly string $iconColor
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header-notification-bell', ['view' => $this]);
    }
}