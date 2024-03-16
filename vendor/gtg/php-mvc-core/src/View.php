<?php 

namespace GTG\MVC;

use GTG\MVC\BuildContext;

final class View 
{
    private BuildContext $buildContext;
    private ?string $errorPagePath = null;

    public function __construct(string $viewsFolderPath) 
    {
        $this->buildContext = new BuildContext($viewsFolderPath);
    }

    public function getContext(): BuildContext 
    {
        return $this->buildContext;
    }

    public function getErrorPagePath(): ?string
    {
        return $this->errorPagePath;
    }

    public function setErrorPagePath(string $errorPagePath): self
    {
        $this->errorPagePath = $errorPagePath;
        return $this;
    }
}