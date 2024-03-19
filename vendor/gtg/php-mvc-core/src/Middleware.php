<?php 

namespace GTG\MVC;

use GTG\MVC\{ Request, Router, Session };

abstract class Middleware 
{
    abstract public function handle(Request $request): bool;

    public function __construct(
        protected ?Router $router = null,
        protected ?Session $session = null
    ) 
    {}

    protected function getRoute(string $route, array $params = []): ?string 
    {
        return $this->router?->route($route, $params);
    }

    protected function redirect(string $routeKey, array $params = []): void 
    {
        header("Location: " . $this->getRoute($routeKey, $params));
        exit();
    }
}