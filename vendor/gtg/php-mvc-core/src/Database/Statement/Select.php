<?php 

namespace GTG\MVC\Database\Statement;

final class Select
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
                $columnsArray[] = is_string($columnName) ? ($columnName . ' AS ' . $alias) : $alias;
            }

            $sql .= implode(', ', $columnsArray);
        }
        
        return $sql;
    }
}