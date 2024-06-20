<?php 

namespace Views\Components\Cards;

use Views\Widget;

final class DefaultCard extends Widget
{
    public function __construct(
        private readonly ?array $children
    ) 
    {}

    public function __toString(): string 
    {
        return '<div class="card shadow mb-4 br-15">' . $this->getChildren() . '</div>';
    }

    private function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}