<?php 

namespace Src\Views\Components\MainLayout;

class Footer 
{
    public function __construct(
        private string $backgroundColor,
        private string $textColor,
        private string $logoURL,
        private string $copyrightText,
        private ?array $socials = null
    ) 
    {}

    public function getBackgroundColor(): string 
    {
        return 'bg-' . $this->backgroundColor;
    }

    public function getTextColor(): string 
    {
        return 'text-' . $this->textColor;
    }

    public function getLogoURL(): string
    {
        return $this->logoURL;
    }

    public function getCopyrightText(): string
    {
        return $this->copyrightText;
    }

    public function hasSocials(): bool
    {
        return $this->socials ? true : false;
    }

    public function getSocials(): ?array
    {
        return $this->socials;
    }
}