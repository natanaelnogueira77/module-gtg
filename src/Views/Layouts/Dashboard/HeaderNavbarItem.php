<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class HeaderNavbarItem extends Widget
{
    public function __construct(
        public readonly string $url,
        public readonly string $iconClass,
        public readonly string $text
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header-navbar-item', ['view' => $this]);
    }
}