<?php 

namespace GTG\MVC\Database\Schema;

final class ProcedureParamDefinition 
{
    public function __construct(
        private string $columnName,
        private string $type,
        private array $params = []
    ) 
    {}

    private function getParam(string $key): mixed 
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    public function build(): string 
    {
        return $this->toMySQL();
    }

    public function toMySQL(): string 
    {
        $sql = "`{$this->columnName}`";
        if($this->type == 'integer') {
            $sql .= ' INT(1)';
        } elseif($this->type == 'tinyInteger') {
            $sql .= ' BOOLEAN';
        } elseif($this->type == 'bigInteger') {
            $sql .= ' BIGINT';
        } elseif($this->type == 'float') {
            $sql .= " FLOAT({$this->getParam('total')}, {$this->getParam('places')})";
        } elseif($this->type == 'char') {
            $sql .= " CHAR({$this->getParam('length')})";
        } elseif($this->type == 'string') {
            $sql .= " VARCHAR({$this->getParam('length')})";
        } elseif($this->type == 'tinyText') {
            $sql .= ' TINYTEXT';
        } elseif($this->type == 'text') {
            $sql .= ' TEXT';
        } elseif($this->type == 'mediumText') {
            $sql .= ' MEDIUMTEXT';
        } elseif($this->type == 'longText') {
            $sql .= ' LONGTEXT';
        } elseif($this->type == 'date') {
            $sql .= ' DATE';
        } elseif($this->type == 'time') {
            $sql .= ' TIME';
        } elseif($this->type == 'dateTime') {
            $sql .= ' DATETIME';
        } elseif($this->type == 'timestamp') {
            $sql .= ' TIMESTAMP';
        }

        return $sql;
    }
}