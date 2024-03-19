<?php 

namespace GTG\MVC\Database\Statement\Clauses;

final class Select extends Clause 
{
    public function __construct(
        private string|array $columns
    )
    {}

    public function build(): string 
    {
        $sql = 'SELECT ';
        if(is_string($this->columns)) {
            $sql .= $this->columns;
        } elseif(is_array($this->columns)) {
            $columnsArray = [];
            foreach($this->columns as $columnName => $alias) {
                if(is_string($columnName)) {
                    $columnsArray[] = $columnName . ' AS ' . $alias;
                } else {
                    $columnsArray[] = $alias;
                }
            }

            $sql .= implode(', ', $columnsArray);
        }
        
        return $sql;
    }
}