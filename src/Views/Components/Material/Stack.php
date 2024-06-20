<?php

namespace Views\Components\Material;

use Views\Widget;

class Stack extends Widget
{
    public function __construct(
        private readonly ?array $children = null
    )
    {}

    public function __toString(): string 
    {
        return $this->children ? implode('', $this->children) : '';
    }
}