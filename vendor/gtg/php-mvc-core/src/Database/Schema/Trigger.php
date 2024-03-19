<?php 

namespace GTG\MVC\Database\Schema;

use GTG\MVC\Database\Schema\ColumnDefinition;

final class Trigger 
{
    protected string $event;
    protected string $statement;

    public function __construct(
        protected string $trigger
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

    public function event(string $event): static 
    {
        $this->event = $event;
        return $this;
    }

    public function statement(string $statement): static 
    {
        $this->statement = $statement;
        return $this;
    }

    public function build(): string 
    {
        if($this->action == 'create') {
            return "CREATE TRIGGER `{$this->trigger}` " . $this->event . ' BEGIN ' . $this->statement . ' END;';
        } elseif($this->action == 'drop') {
            return "DROP TRIGGER `{$this->trigger}`";
        } elseif($this->action == 'drop_if_exists') {
            return "DROP TRIGGER IF EXISTS `{$this->trigger}`";
        }

        return '';
    }
}