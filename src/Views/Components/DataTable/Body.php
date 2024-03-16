<?php 

namespace Src\Views\Components\DataTable;

class Body
{
    public function __construct(
        private ?array $rows = null,
        private int $headersCount = 0,
        private ?string $noRowsMessage = null
    ) 
    {}

    public function hasRows(): bool 
    {
        return $this->rows ? true : false;
    }

    public function getRows(): array 
    {
        return $this->rows;
    }

    public function getHeadersCount(): int 
    {
        return $this->headersCount;
    }

    public function getNoRowsMessage(): string 
    {
        return $this->noRowsMessage ?? _('No corresponding data was found!');
    }
}