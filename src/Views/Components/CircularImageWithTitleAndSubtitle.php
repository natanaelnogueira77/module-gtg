<?php 

namespace Src\Views\Components;

class CircularImageWithTitleAndSubtitle 
{
    public function __construct(
        private string $imageURL,
        private string $title,
        private ?string $subtitle = null
    ) 
    {}

    public function getImageURL(): string 
    {
        return $this->imageURL;
    }

    public function getTitle(): string 
    {
        return $this->title;
    }

    public function getSubtitle(): string 
    {
        return $this->subtitle ?? '';
    }

    public function hasSubtitle(): bool 
    {
        return $this->subtitle ? true : false;
    }
}