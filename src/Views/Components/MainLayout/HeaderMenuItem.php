<?php 

namespace Src\Views\Components\MainLayout;

class HeaderMenuItem 
{
    public function __construct(
        private string $url,
        private string $text,
        private string $textColor,
        private string $icon
    ) 
    {}

    public function getURL(): string 
    {
        return $this->url;
    }

    public function getText(): string 
    {
        return $this->text;
    }

    public function getTextColor(): string 
    {
        return $this->textColor;
    }

    public function getIcon(): string 
    {
        return $this->icon;
    }
}