<?php 

namespace Src\Views\Components\DataTable;

class Head
{
    private bool $isSelected = false;
    private string $sortingType = 'ASC';

    public function __construct(
        private string $columnId,
        private string $text,
        private bool $isSortable = false
    ) 
    {}

    public function getStyles(): string 
    {
        return 'align-middle';
    }

    public function getArrowUpIconStyles(): string 
    {
        return 'icofont-arrow-up text-' . ($this->isSelected() && $this->isAscendentSorting() ? 'secondary' : 'light');
    }

    public function getArrowDownIconStyles(): string 
    {
        return 'icofont-arrow-down text-' . ($this->isSelected() && $this->isDescendentSorting() ? 'secondary' : 'light');
    }

    public function getColumnId(): string 
    {
        return $this->columnId;
    }

    public function getText(): string 
    {
        return $this->text;
    }

    public function setAsSelected(): self 
    {
        $this->isSelected = true;
        return $this;
    }

    public function setAsNotSelected(): self 
    {
        $this->isSelected = false;
        return $this;
    }

    public function setSortingType(string $sortingType): self 
    {
        $this->sortingType = $sortingType;
        return $this;
    }

    public function isSortable(): bool 
    {
        return $this->isSortable;
    }

    public function isSelected(): bool 
    {
        return $this->isSelected;
    }

    public function isAscendentSorting(): bool 
    {
        return $this->sortingType == 'ASC';
    }

    public function isDescendentSorting(): bool 
    {
        return $this->sortingType == 'DESC';
    }
}