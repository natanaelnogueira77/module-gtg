<?php 

namespace Src\Views\Layouts;

use Src\Views\Components\MainLayout\{ Footer, Header, Left };

class Main 
{
    public function __construct(
        private string $applicationName,
        private string $applicationVersion,
        private string $logoURL,
        private string $logoIconURL,
        private string $title,
        private ?Header $header = null,
        private ?Left $left = null,
        private ?Footer $footer = null
    ) 
    {}

    public function getApplicationName(): string 
    {
        return $this->applicationName;
    }

    public function getApplicationVersion(): string 
    {
        return $this->applicationVersion;
    }

    public function getLogoURL(): string
    {
        return $this->logoURL;
    }

    public function getLogoIconURL(): string
    {
        return $this->logoIconURL;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHeader(): ?Header
    {
        return $this->header;
    }

    public function getLeft(): ?Left
    {
        return $this->left;
    }

    public function getFooter(): ?Footer
    {
        return $this->footer;
    }
}