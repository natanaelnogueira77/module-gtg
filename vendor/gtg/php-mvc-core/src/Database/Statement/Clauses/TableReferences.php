<?php 

namespace GTG\MVC\Database\Statement\Clauses;

use GTG\MVC\Database\Statement\Statement;

trait TableReferences 
{
    protected function getTableReferences(): string 
    {
        if(is_string($this->tableReferences)) {
            return $this->tableReferences;
        } elseif(is_array($this->tableReferences)) {
            $tables = [];
            foreach($this->tableReferences as $tableName => $alias) {
                if(is_string($tableName)) {
                    $tables[] = $tableName . ' AS ' . $alias;
                } elseif($tableName instanceof Statement) {
                    $tables[] = '(' . $tableName->getQuery() . ')' . ' AS ' . $alias;
                } else {
                    $tables[] = $alias;
                }
            }
            
            return implode(', ', $tables);
        }
    }
}