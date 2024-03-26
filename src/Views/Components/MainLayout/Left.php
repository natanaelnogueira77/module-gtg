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

    public function getStyles(): string 
    {
        return 'col-1 col-md-3 col-xl-2 px-md-2 px-0 shadow sticky-top ' . $this->getBackgroundColor();
    }

    private function getBackgroundColor(): string 
    {
        return 'bg-' . $this->backgroundColor;
    }

    public function getAsideStyles(): string 
    {
        return 'd-flex flex-column align-items-center align-items-md-start px-md-0 pt-2 h-100 ' 
            . $this->getBackgroundColor() . ' ' . $this->getTextColor();
    }

    private function getTextColor(): string 
    {
        return 'text-' . $this->textColor;
    }

    public function getMenu(): LeftMenu 
    {
        return $this->menu;
    }
}