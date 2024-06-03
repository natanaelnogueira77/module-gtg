<?php 

namespace Src\Controllers;

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

    protected function getViewHTML(string $filename, ?array $data = []): string 
    {
        return $this->getContext()->addData([
            'appData' => $this->appData,
            'router' => $this->router,
            'session' => $this->session
        ])->render($filename, $data);
    }

    protected function getPageHTML(string $filename, ?array $data = []): string
    {
        return $this->getViewHTML("pages/{$filename}", $data);
    }

    protected function getComponentHTML(string $filename, ?array $data = []): string
    {
        return $this->getViewHTML("components/{$filename}", $data);
    }

    protected function getWidgetHTML(string $filename, ?array $data = []): string
    {
        return $this->getViewHTML("widgets/{$filename}", $data);
    }

    protected function getDataTableHTML(string $filename, ?array $data = []): string
    {
        return $this->getWidgetHTML("data-tables/{$filename}", $data);
    }

    protected function getEmailHTML(string $filename, ?array $data = []): string
    {
        return $this->getWidgetHTML("emails/{$filename}", $data);
    }

    protected function getFormHTML(string $filename, ?array $data = []): string
    {
        return $this->getWidgetHTML("forms/{$filename}", $data);
    }

    protected function getPDFHTML(string $filename, ?array $data = []): string
    {
        return $this->getWidgetHTML("pdfs/{$filename}", $data);
    }

    protected function renderPage(string $filename, ?array $data = []): void 
    {
        echo $this->getPageHTML($filename, $data);
    }
}