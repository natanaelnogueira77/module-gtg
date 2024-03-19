<?php 

namespace GTG\MVC\Database\Statement\Clauses;

use GTG\MVC\Database\Statement\Statement;

final class From extends Clause 
{
    public function __construct(
        private string|array $tableReferences
    )
    {}

    use TableReferences;

    public function build(): string 
    {
        return 'FROM ' . $this->getTableReferences();
    }
}