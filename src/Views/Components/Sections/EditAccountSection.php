<?php 

namespace Views\Components\Sections;

use Models\AR\User;
use Views\Components\Forms\SaveUserForm;
use Views\Widget;

class EditAccountSection extends Widget
{
    public function __construct(
        public readonly string $formId,
        public readonly string $action,
        public readonly string $method,
        public readonly string $returnUrl,
        public readonly ?array $userTypes = null, 
        public readonly ?User $user = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/sections/edit-account', ['view' => $this]);
    }

    public function getForm(): SaveUserForm 
    {
        return new SaveUserForm(
            id: $this->formId, 
            action: $this->action,
            method: $this->method,
            isAccountEdit: true,
            user: $this->user,
            userTypes: $this->userTypes
        );
    }
}