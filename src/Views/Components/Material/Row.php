<?php

namespace Views\Components\Material;

use Views\Widget;

class Row extends Widget
{
    public function __construct(
        private readonly array $columns
    )
    {}

    public function __toString(): string 
    {
        return "<div class=\"row\">{$this->getColumns()}</div>";
    }

    public function getColumns(): string 
    {
        return $this->columns ? implode('', $this->columns) : '';
    }
}