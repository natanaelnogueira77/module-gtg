<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class HeaderLanguages extends Widget
{
    public function __construct(
        public readonly string $currentImageUrl,
        private readonly ?array $children = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/header-languages', ['view' => $this]);
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}