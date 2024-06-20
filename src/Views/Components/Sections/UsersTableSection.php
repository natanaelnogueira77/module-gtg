<?php 

namespace Views\Components\Sections;

use Views\Components\{ DataTable\Filters, Material\ActionButton };
use Views\Widget;

class UsersTableSection extends Widget
{
    private Filters $filters;

    public function __construct(
        public readonly string $tableId,
        public readonly string $filtersFormId,
        public readonly string $formId,
        public readonly string $modalId,
        public readonly string $actionButtonId,
        public readonly string $action,
        public readonly string $storeAction,
        public readonly ?array $userTypes
    )
    {
        $this->filters = new Filters(formId: $filtersFormId);
    }

    public function __toString(): string 
    {
        return $this->getContext()->render('components/sections/users-table', ['view' => $this]);
    }

    public function getActionButton(): ActionButton 
    {
        return new ActionButton(
            color: 'primary',
            size: 'lg',
            content: '<i class="icofont-plus"></i> ' . _('Cadastrar Usuário'),
            attributes: [
                'id' => $this->actionButtonId, 
                'data-action' => $this->storeAction, 
                'data-method' => 'post'
            ]
        );
    }

    public function getFilters(): Filters 
    {
        return $this->filters;
    }
}