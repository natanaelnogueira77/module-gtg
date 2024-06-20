<?php 

namespace Views\Components\Material;

use Views\Widget;

class ActionsDropdown extends Widget
{
    public function __construct(
        private readonly ?array $children = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/actions-dropdown', ['view' => $this]);
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}