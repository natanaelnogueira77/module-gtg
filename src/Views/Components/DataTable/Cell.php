<?php 

namespace Src\Views\Components\DataTable;

class Cell
{
    public function __construct(
        private string $columnId, 
        private string $text = ''
    ) 
    {}

    public function getColumnId(): string 
    {
        return $this->columnId;
    }

    public function getText(): string 
    {
        return $this->text;
    }
}