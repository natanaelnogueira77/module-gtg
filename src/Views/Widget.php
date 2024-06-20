<?php 

namespace Views;

use GTG\MVC\View;

abstract class Widget extends View
{
    abstract public function __toString(): string;
}