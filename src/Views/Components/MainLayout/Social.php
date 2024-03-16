<?php 

namespace Src\Views\Components\MainLayout;

class Social 
{
    public function __construct(
        private string $buttonColor,
        private string $url,
        private string $icon
    ) 
    {}

    public function getButtonColor(): string 
    {
        return 'btn-outline-' . $this->buttonColor;
    }

    public function getURL(): string 
    {
        return $this->url;
    }

    public function getIcon(): string 
    {
        return $this->icon;
    }
}