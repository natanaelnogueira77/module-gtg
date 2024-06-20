<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class HeaderAvatarDropdown extends Widget
{
    public function __construct(
        public readonly string $avatarUrl, 
        public readonly string $title, 
        public readonly string $subtitle, 
        private readonly ?array $children = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header-avatar-dropdown', ['view' => $this]);
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}