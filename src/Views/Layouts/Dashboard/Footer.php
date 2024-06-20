<?php 

namespace Views\Layouts\Dashboard;

use Views\Widget;

class Footer extends Widget
{
    public function __construct(
        public readonly string $logoIconUrl, 
        public readonly string $appName, 
        public readonly string $copyrightText
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('layouts/dashboard/footer', ['view' => $this]);
    }
}