<?php 

namespace GTG\MVC\Database\Schema;

use GTG\MVC\Database\Schema\ColumnDefinition;

final class Event 
{
    protected string $schedule;
    protected string $statement;

    public function __construct(
        protected string $event
    ) 
    {}

    public function schedule(string $schedule): static 
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function statement(string $statement): static 
    {
        $this->statement = $statement;
        return $this;
    }

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

    public function build(): string 
    {
        if($this->action == 'create') {
            return "CREATE EVENT `{$this->event}` ON SCHEDULE " . $this->schedule . ' DO ' . $this->statement . ';';
        } elseif($this->action == 'drop') {
            return "DROP EVENT `{$this->event}`";
        } elseif($this->action == 'drop_if_exists') {
            return "DROP EVENT IF EXISTS `{$this->event}`";
        }

        return '';
    }
}