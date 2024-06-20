<?php 

namespace Views\Layouts\Main;

use Views\Widget;

class Theme extends Widget
{
    public function __construct(
        public readonly string $title, 
        public readonly string $logoIconUrl, 
        public readonly ?Widget $body = null, 
        public readonly ?array $scripts = null
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/main/_theme', ['view' => $this]);
    }

    public function getBody(): Widget 
    {
        return $this->body ?? '';
    }

    public function getScripts(): string 
    {
        return $this->scripts ? implode('', array_map(
            fn($type, $script) => "<script type=\"{$type}\" src=\"{$script}\"></script>", 
            $this->scripts, 
            array_keys($this->scripts)
        )) : '';
    }
}