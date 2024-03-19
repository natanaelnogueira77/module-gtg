<?php 

namespace GTG\MVC\Database\Statement\Clauses;

abstract class Clause 
{
    abstract public function build(): string;
}