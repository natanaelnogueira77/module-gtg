<?php 

namespace Views\Components\Forms;

use Models\AR\User;
use Views\Widget;

class SaveUserForm extends Widget
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $action = null,
        public readonly ?string $method = null,
        public readonly ?array $userTypes = null,
        public readonly bool $isAccountEdit = false, 
        public readonly ?User $user = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/forms/save-user', ['view' => $this]);
    }
}