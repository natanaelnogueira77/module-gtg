<?php 

namespace Middlewares;

use GTG\MVC\Middleware as GTGMiddleware;

abstract class Middleware extends GTGMiddleware 
{
    protected function setErrorFlash(string $message): static 
    {
        $this->session->setFlash('error', $message);
        return $this;
    }

    protected function setSuccessFlash(string $message): static 
    {
        $this->session->setFlash('success', $message);
        return $this;
    }

    protected function writeSuccessResponse(?array $data = null): void 
    {
        $this->createResponse($data ?? [], 200)->writeToJSON();
    }

    protected function writeForbiddenResponse(?array $data = null): void 
    {
        $this->createResponse($data ?? [], 403)->writeToJSON();
    }

    protected function writeNotFoundResponse(?array $data = null): void 
    {
        $this->createResponse($data ?? [], 404)->writeToJSON();
    }
}