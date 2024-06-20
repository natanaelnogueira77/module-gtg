<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class Left extends Widget
{
    public function __construct(
        public readonly string $backgroundColor,
        public readonly string $textColor,
        private readonly ?LeftNavbar $navbar = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/left', ['view' => $this]);
    }

    public function getNavbar(): ?LeftNavbar 
    {
        return $this->navbar;
    }
}