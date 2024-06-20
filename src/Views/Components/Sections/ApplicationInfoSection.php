<?php 

namespace Views\Components\Sections;

use Views\Widget;

class ApplicationInfoSection extends Widget
{
    public function __construct(
        public readonly string $id,
        public readonly string $version,
        public readonly array $userTypes,
        public readonly array $usersCount,
        public readonly array $actionButtons,
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/sections/application-info', ['view' => $this]);
    }

    public function getActionButtons(): string 
    {
        return implode('', $this->actionButtons);
    }
}