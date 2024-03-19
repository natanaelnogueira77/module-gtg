<?php 

namespace GTG\MVC;

final class Request 
{
    public function __construct(
        private array $data
    ) 
    {}

    public function isGet(): bool 
    {
        return $this->method() === 'get';
    }

    public function isPost(): bool 
    {
        return $this->method() === 'post';
    }

    public function isPut(): bool 
    {
        return $this->method() === 'put';
    }

    public function isDelete(): bool 
    {
        return $this->method() === 'delete';
    }

    public function isPatch(): bool 
    {
        return $this->method() === 'patch';
    }

    private function method(): string 
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function get(string $paramName): mixed
    {
        return isset($this->getData()[$paramName]) ? $this->getData()[$paramName] : null;
    }

    public function getData(): array 
    {
        return $this->data;
    }
}