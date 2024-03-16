<?php 

namespace Src\Views\Components\DataTable;

class Pagination 
{
    public function __construct(
        private int $selectedPage, 
        private int $limit, 
        private int $totalRows,
        private int $totalPages
    ) 
    {}

    public function getSelectedPage(): int 
    {
        return $this->selectedPage;
    }

    public function getLimit(): int 
    {
        return $this->limit;
    }

    public function getTotalRows(): int 
    {
        return $this->totalRows;
    }

    public function getTotalPages(): int 
    {
        return $this->totalPages;
    }

    public function getFirstResultNumber(): int 
    {
        return $this->limit * ($this->selectedPage - 1) + 1;
    }

    public function getLastResultNumber(): int 
    {
        return $this->selectedPage != $this->totalPages ? $this->limit * $this->selectedPage : $this->totalRows;
    }

    public function isPageNumberOnLoopingCondition(int $pageNumber): bool 
    {
        return $pageNumber <= $this->getTotalPages() 
            && $pageNumber >= $this->getSelectedPage() - (
                $this->getSelectedPage() >= $this->getTotalPages() - 5 
                    ? 10 - ($this->getTotalPages() - $this->getSelectedPage()) 
                    : 5
            ) 
            && $pageNumber <= $this->getSelectedPage() + (
                $this->getSelectedPage() <= 5 
                    ? 10 - $this->getSelectedPage() 
                    : 5
            );
    }

    public function getStartingValueForPageLooping(): int 
    {
        return $this->getSelectedPage() - 5 >= 1 
        ? (
            $this->getSelectedPage() >= $this->getTotalPages() - 5 
            ? ($this->getTotalPages() > 10 ? $this->getTotalPages() - 10 : 1)
            : $this->getSelectedPage() - 5
        ) : 1;
    }
}