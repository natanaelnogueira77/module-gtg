<?php 

namespace Views\Layouts\Dashboard;

use GTG\MVC\Application;
use Views\Widget;

class Theme extends Widget
{
    public function __construct(
        public readonly string $title, 
        public readonly string $logoUrl, 
        public readonly string $logoIconUrl, 
        public readonly ?string $backgroundImageUrl = null, 
        public readonly ?Header $header = null,
        public readonly ?Left $left = null,
        public readonly ?Widget $body = null, 
        public readonly ?Footer $footer = null,
        private readonly ?array $styles = null, 
        private readonly ?array $scripts = null, 
        private readonly ?array $modals = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/_theme', ['view' => $this]);
    }

    public function getHeader(): ?Header 
    {
        return $this->header;
    }

    public function getLeft(): ?Left 
    {
        return $this->left;
    }

    public function getBody(): ?string 
    {
        return $this->body;
    }

    public function getFooter(): ?Footer 
    {
        return $this->footer;
    }

    public function getStyles(): string 
    {
        return $this->styles ? implode('', array_map(fn($style) => "<link rel=\"stylesheet\" href=\"{$style}\">", $this->styles)) : '';
    }

    public function getScripts(): string 
    {
        return $this->scripts ? implode('', array_map(
            fn($type, $script) => "<script type=\"{$type}\" src=\"{$script}\"></script>", 
            $this->scripts, 
            array_keys($this->scripts)
        )) : '';
    }

    public function getModals(): string 
    {
        return $this->modals ? implode('', $this->modals) : '';
    }

    public function getMessages(): array 
    {
        $session = Application::getInstance()->session;
        return array_combine(
            $types = array_filter(['success', 'error', 'info'], fn($type) => $session->getFlash($type) !== false), 
            array_map(fn($type) => $session->getFlash($type), $types)
        );
    }
}