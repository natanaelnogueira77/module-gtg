<?php 

namespace Views\Components\Forms;

use Views\Widget;

class ConfigForm extends Widget
{
    public function __construct(
        public readonly string $id,
        public readonly string $action,
        public readonly string $method,
        public readonly ?array $configValues = null
    )
    {}

    public function __toString(): string 
    {
        return $this->getContext()->render('components/forms/config', ['view' => $this]);
    }
}