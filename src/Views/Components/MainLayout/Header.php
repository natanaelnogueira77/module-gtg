<?php 

namespace Src\Views\Components\MainLayout;

class Header 
{
    public function __construct(
        private string $backgroundColor,
        private string $textColor,
        private string $logoURL,
        private ?HeaderMenu $menu = null, 
        private ?HeaderRight $right = null,
        private bool $hasLeft = false
    ) 
    {}

    public function getBackgroundColor(): string 
    {
        return 'bg-' . $this->backgroundColor;
    }

    public function getTextColor(): string 
    {
        return 'text-' . $this->textColor;
    }

    public function getLogoURL(): string
    {
        return $this->logoURL;
    }

    public function getMenu(): ?HeaderMenu
    {
        return $this->menu;
    }

    public function getRight(): ?HeaderRight
    {
        return $this->right;
    }

    public function hasLeft(): bool 
    {
        return $this->hasLeft;
    }
}