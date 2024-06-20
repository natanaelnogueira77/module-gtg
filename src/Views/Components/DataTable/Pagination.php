<?php 

namespace Views\Components\DataTable;

use Models\Lists\ActiveRecordList;
use Views\Widget;

class Pagination extends Widget 
{
    public function __construct(
        public ActiveRecordList $list,
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/pagination', ['view' => $this]);
    }

    public function getSelectedPage(): int 
    {
        return $this->list->getPageToShow();
    }

    public function getLimit(): int 
    {
        return $this->list->getLimit();
    }

    public function getTotalRows(): int 
    {
        return $this->list->getResultsCount();
    }

    public function getTotalPages(): int 
    {
        return $this->list->getTotalPages();
    }

    public function getStartingValueForPageLooping(): int 
    {
        $selectedPage = $this->getSelectedPage();
        $totalPages = $this->getTotalPages();
        return $selectedPage - 5 >= 1 ? (
            $selectedPage >= $totalPages - 5 ? ($totalPages > 10 ? $totalPages - 10 : 1) : $selectedPage - 5
        ) : 1;
    }

    public function isPageNumberOnLoopingCondition(int $pageNumber): bool 
    {
        $selectedPage = $this->getSelectedPage();
        $totalPages = $this->getTotalPages();

        return $pageNumber <= $totalPages && $pageNumber >= $selectedPage - (
            $selectedPage >= $totalPages - 5 ? 10 - ($totalPages - $selectedPage) : 5
        ) && $pageNumber <= $selectedPage + (
            $selectedPage <= 5 ? 10 - $selectedPage : 5
        );
    }
}