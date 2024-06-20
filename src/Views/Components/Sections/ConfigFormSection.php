<?php 

namespace Views\Components\Sections;

use Views\Components\Forms\ConfigForm;
use Views\Widget;

class ConfigFormSection extends Widget
{
    public function __construct(
        public readonly string $formId,
        public readonly string $action,
        public readonly string $method,
        public readonly ?array $configValues = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/sections/config-form', ['view' => $this]);
    }

    public function getForm(): string 
    {
        return new ConfigForm(
            id: $this->formId, 
            action: $this->action,
            method: $this->method,
            configValues: $this->configValues
        );
    }
}