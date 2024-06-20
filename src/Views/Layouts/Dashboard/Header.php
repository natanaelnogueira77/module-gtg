<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class Header extends Widget
{
    public function __construct(
        public readonly string $backgroundColor,
        public readonly string $textColor,
        public readonly string $logoUrl,
        private readonly bool $hasLeft = false,
        private readonly ?HeaderNavbar $navbar = null,
        private readonly ?HeaderRight $right = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header', ['view' => $this]);
    }

    public function hasLeft(): bool 
    {
        return $this->hasLeft;
    }

    public function getNavbar(): ?HeaderNavbar 
    {
        return $this->navbar;
    }

    public function getRight(): ?HeaderRight 
    {
        return $this->right;
    }
}