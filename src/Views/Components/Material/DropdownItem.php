<?php 

namespace Views\Components\Material;

use Views\Widget;

class DropdownItem extends Widget
{
    public function __construct(
        public readonly string $type = 'action',
        public readonly ?string $url = null,
        public readonly ?string $content = null,
        public readonly ?array $attributes = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/dropdown-item', ['view' => $this]);
    }

    public function getAttributes(): string 
    {
        return $this->attributes ? implode(' ', array_map(
            fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
            array_keys($this->attributes), 
            array_values($this->attributes)
        )) : '';
    }

    public function isAction(): bool 
    {
        return $this->type == 'action';
    }

    public function isLink(): bool 
    {
        return $this->type == 'link';
    }

    public function isHeader(): bool 
    {
        return $this->type == 'header';
    }

    public function isDivider(): bool 
    {
        return $this->type == 'divider';
    }
}