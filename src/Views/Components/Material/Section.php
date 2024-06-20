<?php

namespace Views\Components\Material;

use Views\Widget;

class Section extends Widget
{
    public function __construct(
        private readonly string $id,
        private readonly ?array $children = null
    )
    {}

    public function __toString(): string 
    {
        return "<section id=\"{$this->id}\">{$this->getChildren()}</section>";
    }

    public function getChildren(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}