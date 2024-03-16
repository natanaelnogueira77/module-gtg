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

    public function hasItems(): bool 
    {
        return $this->items ? true : false;
    }

    public function getItems(): ?array 
    {
        return $this->items;
    }
}