<?php 

namespace Views\Components\Sections;

use Views\Widget;

class LayoutTitleSection extends Widget
{
    public function __construct(
        public readonly string $title,
        public readonly string $subtitle,
        public readonly string $icon,
        public readonly string $iconColor
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/sections/layout-title', ['view' => $this]);
    }
}