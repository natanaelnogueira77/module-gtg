<?php 

namespace Views\Components\Modals;

use GTG\MVC\Application;
use Views\Widget;

class ExpiredSessionLoginModal extends Widget
{
    public string $action;
    public string $method;
    public string $returnUrl;
    public string $expiredUrl;

    public function __construct(
        public readonly string $id
    )
    {
        $router = Application::getInstance()->router;
        $this->action = $router->route('auth.check');
        $this->method = 'post';
        $this->returnUrl = $router->route('auth.index');
        $this->expiredUrl = $router->route('auth.expired');
    }

    public function __toString(): string 
    {
        return $this->getContext()->render('components/modals/expired-session-login', ['view' => $this]);
    }
}