<?php 

namespace Src\Views\Components\MainLayout;

class LeftMenu 
{
    public function __construct(
        private ?array $items = null,
        private ?string $currentURL = null
    ) 
    {
        if($this->items && $this->currentURL) {
            foreach($this->items as $item) {
                if($item->getURL() == $this->currentURL) {
                    $item->setAsActive();
                }
            }
        }
    }

    public function getStyles(): string 
    {
        return 'nav nav-pills mb-md-auto mb-0 align-items-center align-items-md-start overflow-auto vh-100 flex-column';
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