<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class HeaderRight extends Widget
{
    public function __construct(
        private readonly ?array $children = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header-right', ['view' => $this]);
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}