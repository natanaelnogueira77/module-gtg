<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class LeftNavbarItem extends Widget
{
    public function __construct(
        public readonly string $text, 
        private readonly ?string $currentRoute = null, 
        public readonly bool $isHeading = false,
        public readonly ?string $url = null, 
        public readonly ?string $iconClass = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/left-navbar-item', ['view' => $this]);
    }

    public function getStyles(): string 
    {
        return in_array($this->currentRoute, array_merge(
            $this->children ? array_map(fn($child) => $child->url, $this->children) : [], 
            [$this->url]
        )) ? 'mm-active' : '';
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}