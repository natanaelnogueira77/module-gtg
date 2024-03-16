<?php 

namespace GTG\MVC;

use League\Plates\Engine;

class BuildContext 
{
    private Engine $engine;
    
    public function __construct(string $viewsFolderPath) 
    {
        $this->engine = new Engine($viewsFolderPath, 'php');
    }

    public function render(string $viewName, array $data = array()): string
    {
        return $this->engine->render($viewName, $data);
    }

    public function addData(array $data): self
    {
        $this->engine->addData($data);
        return $this;
    }
}