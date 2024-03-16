<?php 

namespace Src\Views\Components\MainLayout;

class HeaderRight 
{
    public function __construct(
        private ?HeaderAvatarDropdown $avatarDropdown = null,
        private ?HeaderNotificationBell $notificationBell = null,
        private ?HeaderLanguages $languages = null,
        private bool $isLogged = false
    ) 
    {}

    public function isLogged(): bool 
    {
        return $this->isLogged;
    }

    public function getAvatarDropdown(): ?HeaderAvatarDropdown 
    {
        return $this->avatarDropdown;
    }

    public function hasNotificationBell(): bool 
    {
        return $this->notificationBell ? true : false;
    }

    public function getNotificationBell(): ?HeaderNotificationBell 
    {
        return $this->notificationBell;
    }

    public function hasLanguages(): bool 
    {
        return $this->languages ? true : false;
    }

    public function getLanguages(): ?HeaderLanguages 
    {
        return $this->languages;
    }
}