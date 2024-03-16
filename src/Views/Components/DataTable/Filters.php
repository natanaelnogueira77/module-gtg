<?php 

namespace Src\Views\Components\DataTable;

class Filters
{
    public function __construct(
        private bool $hasLimitFilter = true, 
        private bool $hasSearchFilter = true
    ) 
    {}

    public function hasLimitFilter(): bool 
    {
        return $this->hasLimitFilter;
    }

    public function hasSearchFilter(): bool 
    {
        return $this->hasSearchFilter;
    }
}