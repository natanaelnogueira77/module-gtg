<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class Join extends Clause 
{
    private array $operators = [];

    public function __construct(
        private string|array $tableReferences
    )
    {}

    use Conditions;
    use TableReferences;

    public function build(): string 
    {
        return $this->operators ? ('JOIN ' . $this->getTableReferences() . ' ON ' . $this->getConditions()) : '';
    }
}