<?php 

namespace Views\Components\Material;

use Views\Widget;

class Badge extends Widget
{
    public function __construct(
        public readonly string $color,
        public readonly string $content,
        private readonly ?array $attributes = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/badge', ['view' => $this]);
    }

    public function getAttributes(): string 
    {
        return $this->attributes ? implode(' ', array_map(
            fn($key, $value) => $key ? "{$key}=\"{$value}\"" : "{$value}", 
            array_keys($this->attributes), 
            array_values($this->attributes)
        )) : '';
    }
}