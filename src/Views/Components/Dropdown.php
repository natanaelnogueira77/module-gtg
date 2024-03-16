<?php 

namespace Src\Views\Components;

class Dropdown 
{
    public function __construct(
        private string $buttonColor,
        private string $buttonSize,
        private string $text,
        private DropdownMenu $menu,
        private string $dropdownDirection = 'up',
    ) 
    {}

    public function getStyles(): string 
    {
        return "dropdown drop{$this->dropdownDirection}";
    }

    private function getDropdownDirection(): string 
    {
        return "drop{$this->dropdownDirection}";
    }

    public function getButtonStyles(): string 
    {
        return "dropdown-toggle btn {$this->getButtonSize()} {$this->getButtonColor()}";
    }

    private function getButtonSize(): string 
    {
        return "btn-{$this->buttonSize}";
    }

    private function getButtonColor(): string 
    {
        return "btn-{$this->buttonColor}";
    }

    public function getText(): string 
    {
        return $this->text;
    }

    public function getDropdownMenu(): DropdownMenu 
    {
        return $this->menu;
    }
}