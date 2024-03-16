<?php 

namespace Src\Views\Widgets\Sections;

class UsersList 
{
    public function __construct(
        private string $formId,
        private string $buttonId,
        private string $tableId,
        private string $modalId,
        private string $filtersFormId,
        private array $userTypes
    ) 
    {}

    public function getUserTypes(): array 
    {
        return $this->userTypes;
    }

    public function getFormId(): string 
    {
        return $this->formId;
    }

    public function getButtonId(): string 
    {
        return $this->buttonId;
    }

    public function getTableId(): string 
    {
        return $this->tableId;
    }

    public function getModalId(): string 
    {
        return $this->modalId;
    }

    public function getFiltersFormId(): string 
    {
        return $this->filtersFormId;
    }
}