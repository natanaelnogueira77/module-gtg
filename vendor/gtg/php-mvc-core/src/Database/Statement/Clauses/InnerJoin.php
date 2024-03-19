<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class InnerJoin extends Clause 
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
        return $this->operators ? ('INNER JOIN ' . $this->getTableReferences() . ' ON ' . $this->getConditions()) : '';
    }
}