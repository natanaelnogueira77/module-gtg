<?php 

namespace Src\Views\Components;

class DropdownMenu 
{
    public function __construct(
        private ?array $items = null
    ) 
    {}

    public function getStyles(): string 
    {
        return "dropdown-menu";
    }

    public function hasItems(): bool 
    {
        return $this->items ? true : false;
    }

    public function getItems(): ?array 
    {
        return $this->items;
    }
}