<?php 

namespace Src\Views\Components;

class LayoutTitle 
{
    public function __construct(
        private string $title,
        private string $subtitle,
        private ?string $icon = null,
        private ?string $iconColor = null
    ) 
    {}

    public function getIconStyles(): string 
    {
        return "fs-1 {$this->icon} {$this->iconColor}";
    }

    public function hasIcon(): bool 
    {
        return $this->icon ? true : false;
    }

    public function getTitle(): string 
    {
        return $this->title;
    }

    public function getSubtitle(): string 
    {
        return $this->subtitle;
    }
}