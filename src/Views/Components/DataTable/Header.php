<?php 

namespace Src\Views\Components\DataTable;

class Header 
{
    private ?string $selectedColumnId = null;
    private ?string $selectedOrderType = null;

    public function __construct(
        private array $heads = []
    ) 
    {}

    public function getHeads(): array 
    {
        if($this->heads && $this->selectedColumnId && $this->selectedOrderType) {
            foreach($this->heads as $head) {
                if($head->getColumnId() == $this->selectedColumnId) {
                    $head->setAsSelected();
                    $head->setSortingType($this->selectedOrderType);
                    break;
                }
            }
        }
        return $this->heads;
    }

    public function setSelectedColumnId(string $columnId): self 
    {
        $this->selectedColumnId = $columnId;
        return $this;
    }

    public function setSelectedOrderType(string $orderType): self 
    {
        $this->selectedOrderType = $orderType;
        return $this;
    }
}