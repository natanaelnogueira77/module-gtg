<?php 

namespace Src\Views\Components\MainLayout;

class HeaderNotificationBell 
{
    public function __construct(
        private string $iconColor,
        private ?array $notifications = null
    ) 
    {}

    public function hasNotifications(): bool 
    {
        return $this->notifications ? true : false;
    }

    public function getNotifications(): ?array 
    {
        return $this->notifications;
    }

    public function getNotificationsCount(): int 
    {
        return $this->notifications ? count($this->notifications) : 0;
    }

    public function getIconColor(): string 
    {
        return $this->iconColor;
    }
}