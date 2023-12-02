<?php 

namespace GTG\MVC;

use League\Plates\Engine;

class View extends Engine
{
    public function __construct(string $path) 
    {
        return parent::__construct($path, 'php');
    }
}