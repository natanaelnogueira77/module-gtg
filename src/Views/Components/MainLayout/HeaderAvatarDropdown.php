<?php 

namespace Src\Views\Components\MainLayout;

class HeaderAvatarDropdown 
{
    public function __construct(
        private string $avatarLinkColor,
        private string $avatarImageUrl,
        private ?array $items = null
    ) 
    {}

    public function getAvatarLinkColor(): string 
    {
        return $this->avatarLinkColor;
    }

    public function getAvatarImageURL(): string 
    {
        return $this->avatarImageUrl;
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