<?php 

namespace GTG\MVC;

use GTG\MVC\Application;
use GTG\MVC\Request;

class Router
{
    protected string $projectURL;
    protected string $httpMethod;
    protected string $path;
    protected ?array $route = null;
    protected array $routes;
    protected string $separator;
    protected ?string $namespace = null;
    protected ?string $group = null;
    protected ?array $middleware = null;
    protected ?array $data = null;
    protected ?int $error = null;
    
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_IMPLEMENTED = 501;

    public function __construct(string $projectURL) 
    {
        $this->projectURL = substr($projectURL, "-1") == "/" ? substr($projectURL, 0, -1) : $projectURL;
        $this->path = rtrim(filter_input(INPUT_GET, "route", FILTER_DEFAULT) ?? "/", "/");
        $this->separator = $separator ?? ":";
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function __debugInfo()
    {
        return $this->routes;
    }

    public function namespace(?string $namespace): self
    {
        $this->namespace = $namespace ? ucwords($namespace) : null;
        return $this;
    }

    public function group(?string $group, array|string $middleware = null): self
    {
        $this->group = $group ? trim($group, "/") : null;
        $this->middleware = $middleware ? [$this->group => $middleware] : null;
        return $this;
    }

    public function get(
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void 
    {
        $this->addRoute('GET', $route, $handler, $name, $middleware);
    }

    public function post(
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void {
        $this->addRoute('POST', $route, $handler, $name, $middleware);
    }

    public function put(
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void 
    {
        $this->addRoute('PUT', $route, $handler, $name, $middleware);
    }

    public function patch(
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void 
    {
        $this->addRoute('PATCH', $route, $handler, $name, $middleware);
    }

    public function delete(
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void 
    {
        $this->addRoute('DELETE', $route, $handler, $name, $middleware);
    }

    private function addRoute(
        string $method,
        string $route,
        callable|string $handler,
        string $name = null,
        array|string $middleware = null
    ): void 
    {
        $route = rtrim($route, '/');
        $this->setRouteData($route);

        $route = !$this->group ? $route : "/{$this->group}{$route}";
        $data = $this->data;
        $namespace = $this->namespace;
        $middleware = $middleware ?? (!empty($this->middleware[$this->group]) ? $this->middleware[$this->group] : null);
        $router = function () use ($method, $handler, $data, $route, $name, $namespace, $middleware) {
            return [
                'route' => $route,
                'name' => $name,
                'method' => $method,
                'middlewares' => $middleware,
                'handler' => $this->handler($handler, $namespace),
                'action' => $this->action($handler),
                'data' => $data
            ];
        };

        $route = preg_replace('~{([^}]*)}~', "([^/]+)", $route);
        $this->routes[$method][$route] = $router();
    }

    private function setRouteData(string $route): self 
    {
        $removeGroupFromPath = $this->group ? str_replace($this->group, "", $this->path) : $this->path;
        $pathAssoc = trim($removeGroupFromPath, "/");
        $routeAssoc = trim($route, "/");

        preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $routeAssoc, $keys, PREG_SET_ORDER);
        $routeDiff = array_values(array_diff_assoc(explode('/', $pathAssoc), explode('/', $routeAssoc)));

        $this->formSpoofing();
        $offset = 0;
        foreach($keys as $key) {
            $this->data[$key[1]] = $routeDiff[$offset++] ?? null;
        }
        return $this;
    }

    private function formSpoofing(): void
    {
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(!empty($post['_method']) && in_array($post['_method'], ["PUT", "PATCH", "DELETE"])) {
            $this->httpMethod = $post['_method'];
            $this->data = $post;
            
            unset($this->data["_method"]);
            return;
        } elseif($this->httpMethod == "POST") {
            $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            unset($this->data["_method"]);
            return;
        } elseif(in_array($this->httpMethod, ["PUT", "PATCH", "DELETE"]) && !empty($_SERVER['CONTENT_LENGTH'])) {
            parse_str(
                file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), 
                $putPatch
            );
            $this->data = $putPatch;

            unset($this->data["_method"]);
            return;
        }

        $this->data = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? [];
    }

    private function handler(callable|string $handler, ?string $namespace): callable|string
    {
        return !is_string($handler) ? $handler : "{$namespace}\\" . explode($this->separator, $handler)[0];
    }

    private function action(callable|string $handler): ?string
    {
        return !is_string($handler) ?: (explode($this->separator, $handler)[1] ?? null);
    }

    public function redirect(string $route, array $data = null): void
    {
        if($name = $this->route($route, $data)) {
            header("Location: {$name}");
            exit;
        }

        if(filter_var($route, FILTER_VALIDATE_URL)) {
            header("Location: {$route}");
            exit;
        }

        $route = (substr($route, 0, 1) == "/" ? $route : "/{$route}");
        header("Location: {$this->getRouteURL($route)}");
        exit;
    }

    public function route(string $routeKey, ?array $routeData = null): ?string
    {
        foreach($this->routes as $httpVerb) {
            foreach($httpVerb as $routeItem) {
                if(!empty($routeItem['name']) && $routeItem['name'] == $routeKey) {
                    return $this->treat($routeItem, $routeData);
                }
            }
        }
        return null;
    }

    private function treat(array $routeItem, array $routeData = null): ?string
    {
        $route = $routeItem['route'];
        if(!empty($routeData)) {
            $arguments = [];
            $params = [];
            foreach($routeData as $key => $value) {
                if(!strstr($route, "{{$key}}")) {
                    $params[$key] = $value;
                }
                $arguments["{{$key}}"] = $value;
            }
            $route = $this->process($route, $arguments, $params);
        }

        return $this->getRouteURL($route);
    }

    private function getRouteURL(string $route): string
    {
        return "{$this->projectURL}{$route}";
    }

    private function process(string $route, array $arguments, ?array $params = null): string
    {
        $params = !empty($params) ? '?' . http_build_query($params) : null;
        return str_replace(array_keys($arguments), array_values($arguments), $route) . "{$params}";
    }

    public function data(): ?array
    {
        return $this->data;
    }

    public function current(): ?object
    {
        return (object) array_merge(
            [
                'namespace' => $this->namespace,
                'group' => $this->group,
                'path' => $this->path
            ],
            $this->route ?? []
        );
    }

    public function home(): string
    {
        return $this->projectURL;
    }

    public function dispatch(): bool
    {
        if(empty($this->routes) || empty($this->routes[$this->httpMethod])) {
            $this->error = self::NOT_IMPLEMENTED;
            return false;
        }

        $this->route = null;
        foreach($this->routes[$this->httpMethod] as $key => $route) {
            if(preg_match("~^" . $key . "$~", $this->path, $found)) {
                $this->route = $route;
            }
        }

        return $this->executeController();
    }

    private function executeController(): bool
    {
        if(!$this->route) {
            $this->error = self::NOT_FOUND;
            return false;
        }

        if(!$this->executeMiddlewares()) {
            return false;
        }

        if(is_callable($this->route['handler'])) {
            call_user_func(
                $this->route['handler'], 
                $this->createRequest()
            );
            return true;
        }

        $controller = $this->route['handler'];
        $method = $this->route['action'];

        if(!class_exists($controller)) {
            $this->error = self::BAD_REQUEST;
            return false;
        }

        $newController = new $controller();
        if(!method_exists($controller, $method)) {
            $this->error = self::METHOD_NOT_ALLOWED;
            return false;
        }

        $newController->$method($this->createRequest());
        return true;
    }

    private function executeMiddlewares(): bool
    {
        if(empty($this->route['middlewares'])) {
            return true;
        }

        $middlewares = is_array(
            $this->route['middlewares']
        ) ? $this->route['middlewares'] : [$this->route['middlewares']];

        foreach($middlewares as $middleware) {
            if(!class_exists($middleware)) {
                $this->error = self::NOT_IMPLEMENTED;
                return false;
            }

            $newMiddleware = new $middleware(
                Application::getInstance()->router, 
                Application::getInstance()->session
            );
            
            if(!method_exists($newMiddleware, 'handle')) {
                $this->error = self::METHOD_NOT_ALLOWED;
                return false;   
            }

            if(!$newMiddleware->handle($this->createRequest())) {
                return false;
            }
        }

        return true;
    }

    private function createRequest(): Request
    {
        return new Request($this->route['data'] ?? []);
    }

    public function error(): ?int
    {
        return $this->error;
    }
}