<?php 

namespace Src\Views\Components\MainLayout;

class Left 
{
    public function __construct(
        private string $backgroundColor,
        private string $textColor,
        private ?LeftMenu $menu = null
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

    public function getMenu(): LeftMenu 
    {
        return $this->menu;
    }
}