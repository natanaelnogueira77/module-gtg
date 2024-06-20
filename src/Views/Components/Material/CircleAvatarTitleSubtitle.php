<?php 

namespace Views\Components\Material;

use Views\Widget;

class CircleAvatarTitleSubtitle extends Widget
{
    public function __construct(
        public readonly string $imageUrl,
        public readonly string $title,
        public readonly string $subtitle
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/circle-avatar-title-subtitle', ['view' => $this]);
    }
}