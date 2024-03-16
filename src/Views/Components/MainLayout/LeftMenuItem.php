<?php 

namespace Src\Views\Components\MainLayout;

class LeftMenuItem 
{
    private bool $isActive = false;

    public function __construct(
        private string $url,
        private string $text,
        private string $textColor,
        private string $icon,
        private ?string $itemsListId = null,
        private ?array $items = null
    ) 
    {}

    public function getLinkStyles(): string 
    {
        return 'nav-link px-2 ' . ($this->hasItems() ? 'py-2' : '') . ' align-middle ' 
            . ($this->isActive() ? 'active' : '');
    }

    public function hasItems(): bool
    {
        return $this->items ? true : false;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setAsActive(): self 
    {
        $this->isActive = true;
        return $this;
    }

    public function getItemsListId(): string
    {
        return $this->itemsListId ?? '';
    }

    public function getURL(): string
    {
        return $this->url;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }
}