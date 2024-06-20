<?php 

namespace Views\Components\Modals;

use Views\Components\Forms\SaveUserForm;
use Views\Widget;

class SaveUserModal extends Widget
{
    public function __construct(
        public readonly string $id, 
        public readonly string $formId, 
        public readonly ?array $userTypes
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/modals/save-user', ['view' => $this]);
    }

    public function getForm(): SaveUserForm
    {
        return new SaveUserForm(
            id: $this->formId, 
            userTypes: $this->userTypes
        );
    }
}