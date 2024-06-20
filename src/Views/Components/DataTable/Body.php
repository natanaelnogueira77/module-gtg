<?php 

namespace Views\Components\DataTable;

use Views\Widget;

class Body extends Widget 
{
    public int $headsCount = 0;

    public function __construct(
        private readonly ?array $rows = null,
        public readonly ?string $noRowsMessage = null, 
    ) 
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/data-table/body', ['view' => $this]);
    }

    public function setHeadsCount(int $count): void
    {
        $this->headsCount = $count;
    }

    public function getRows(): string 
    {
        return $this->rows ? implode('', $this->rows) : '';
    }
}