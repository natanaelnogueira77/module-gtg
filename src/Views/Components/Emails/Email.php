<?php 

namespace Views\Components\Emails;

use GTG\MVC\Application;
use Models\AR\Config;
use Views\Widget;

abstract class Email extends Widget
{
    abstract public function __toString(): string;

    public function getHeader(): string 
    {
        return $this->getContext()->render('components/emails/partials/header', [
            'siteName' => Application::getInstance()->appData['app_name'], 
            'logoUrl' => Config::getLogoIconURL()
        ]);
    }
}