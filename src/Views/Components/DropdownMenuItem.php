<?php 

namespace Src\Views\Components;

class DropdownMenuItem 
{
    public const HEADER_TYPE = 1;
    public const BUTTON_TYPE = 2;
    public const LINK_TYPE = 3;

    public function __construct(
        private int $type,
        private string $text,
        private bool $hasDivider = false,
        private ?array $attributes = null,
        private ?string $url = null
    ) 
    {}

    public function isHeader(): bool 
    {
        return $this->type == self::HEADER_TYPE;
    }

    public function isButton(): bool 
    {
        return $this->type == self::BUTTON_TYPE;
    }

    public function isLink(): bool 
    {
        return $this->type == self::LINK_TYPE;
    }

    public function getText(): string 
    {
        return $this->text;
    }

    public function getAttributes(): string 
    {
        return $this->attributes ? implode(' ', array_map(function($key, $value) {
            return "{$key}=\"{$value}\"";
        }, array_keys($this->attributes), $this->attributes)) : '';
    }

    public function getURL(): string 
    {
        return $this->url ?? '';
    }

    public function hasDivider(): bool 
    {
        return $this->hasDivider;
    }
}