<?php 

namespace Src\Views\Components\MainLayout;

class HeaderMenu 
{
    public function __construct(
        private ?array $items = null
    ) 
    {}

    public function hasItems(): bool 
    {
        return $this->items ? true : false;
    }

    public function getItems(): ?array 
    {
        return $this->items;
    }
}