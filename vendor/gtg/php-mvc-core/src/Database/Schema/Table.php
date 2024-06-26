<?php 

namespace GTG\MVC\Database\Schema;

final class Table 
{
    protected string $action;
    protected string $columnAction;
    protected array $columnParams = [];
    protected array $columns = [];

    public function __construct(
        protected string $table
    ) 
    {}

    public function create(): static 
    {
        $this->setAction('create');
        return $this;
    }

    protected function setAction(string $action): static 
    {
        $this->action = $action;
        return $this;
    }

    public function alter(): static 
    {
        $this->setAction('alter');
        return $this;
    }

    public function drop(): static 
    {
        $this->setAction('drop');
        return $this;
    }

    public function dropIfExists(): static 
    {
        $this->setAction('drop_if_exists');
        return $this;
    }

    public function id(string $columnName = 'id'): ColumnDefinition 
    {
        return $this->integer($columnName, true)->primaryKey();
    }

    public function integer(string $columnName, bool $autoIncrement = false): ColumnDefinition
    {
        return $this->addColumn($columnName, 'integer', compact('autoIncrement'));
    }

    private function addColumn(string $columnName, string $type = '', array $params = []): ColumnDefinition 
    {
        if($this->action == 'alter') {
            $params['command'] = isset($this->columnAction) && $this->columnAction != '' ? $this->columnAction : 'add_column';
            if($this->columnParams) $params = array_merge($params, $this->columnParams);

            $this->columnAction = '';
            $this->columnParams = [];
        }

        $definition = new ColumnDefinition($columnName, $type, $params);
        $this->columns[] = $definition;
        return $definition;
    }

    public function tinyInteger(string $columnName, bool $autoIncrement = false): ColumnDefinition
    {
        return $this->addColumn($columnName, 'tinyInteger', compact('autoIncrement'));
    }

    public function bigInteger(string $columnName, bool $autoIncrement = false): ColumnDefinition
    {
        return $this->addColumn($columnName, 'bigInteger', compact('autoIncrement'));
    }

    public function float(string $columnName, int $total = 8, int $places = 2): ColumnDefinition 
    {
        return $this->addColumn($columnName, 'float', compact('total', 'places'));
    }

    public function char(string $columnName, int $length): ColumnDefinition
    {
        return $this->addColumn($columnName, 'char', compact('length'));
    }

    public function string(string $columnName, int $length): ColumnDefinition
    {
        return $this->addColumn($columnName, 'string', compact('length'));
    }

    public function tinyText(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'tinyText');
    }

    public function text(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'text');
    }

    public function mediumText(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'mediumText');
    }

    public function longText(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'longText');
    }

    public function date(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'date');
    }

    public function time(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'time');
    }

    public function dateTime(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'dateTime');
    }

    public function timestamp(string $columnName): ColumnDefinition
    {
        return $this->addColumn($columnName, 'timestamp');
    }

    public function timestamps(): static
    {
        $this->timestamp('created_at')->default('CURRENT_TIMESTAMP')->nullable();
        $this->timestamp('updated_at')->default('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->nullable();
        return $this;
    }

    public function modifyColumn(): static 
    {
        $this->setColumnAction('modify_column');
        return $this;
    }

    private function setColumnAction(string $action): static 
    {
        $this->columnAction = $action;
        return $this;
    }

    public function changeColumn(string $from): static 
    {
        $this->setColumnAction('change_column');
        $this->setColumnParams(['from' => $from]);
        return $this;
    }

    private function setColumnParams(array $params = []): static 
    {
        $this->columnParams = $params;
        return $this;
    }

    public function dropColumn(string $columnName): ColumnDefinition 
    {
        $this->setColumnAction('drop_column');
        return $this->addColumn($columnName);
    }

    public function renameTable(string $tableName): ColumnDefinition 
    {
        $this->setColumnAction('rename_table');
        return $this->addColumn($tableName);
    }

    public function build(): string 
    {
        if($this->action == 'create') {
            return "CREATE TABLE `{$this->table}` (" . implode(',', array_map(fn($column) => $column->build(), $this->columns)) . ');';
        } elseif($this->action == 'alter') {
            return "ALTER TABLE `{$this->table}` " . implode(',', array_map(fn($column) => $column->build(), $this->columns)) . ';';
        } elseif($this->action == 'drop') {
            return "DROP TABLE `{$this->table}`";
        } elseif($this->action == 'drop_if_exists') {
            return "DROP TABLE IF EXISTS `{$this->table}`";
        }

        return '';
    }
}