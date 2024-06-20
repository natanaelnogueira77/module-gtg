<?php 

namespace Controllers;

use GTG\MVC\Controller as GTGController;

abstract class Controller extends GTGController 
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

    protected function writeUnprocessableEntityResponse(?array $data = null): void 
    {
        $this->createResponse($data ?? [], 422)->writeToJSON();
    }
}