<?php

namespace Views\Components\Material;

use Views\Widget;

enum ColumnSizes: string 
{
    case Auto = 'auto';
    case U1 = '1';
    case U2 = '2';
    case U3 = '3';
    case U4 = '4';
    case U5 = '5';
    case U6 = '6';
    case U7 = '7';
    case U8 = '8';
    case U9 = '9';
    case U10 = '10';
    case U11 = '11';
    case U12 = '12';
}

class Column extends Widget
{
    public function __construct(
        private readonly Widget $child, 
        private ColumnSizes $size = ColumnSizes::U12,
        private ?ColumnSizes $smallDeviceSize = null,
        private ?ColumnSizes $mediumDeviceSize = null,
        private ?ColumnSizes $largeDeviceSize = null,
        private ?ColumnSizes $extraLargeDeviceSize = null
    )
    {}

    public function __toString(): string 
    {
        return "<div class=\"{$this->getStyles()}\">{$this->child}</div>";
    }

    private function getStyles(): string 
    {
        return implode(' ', array_filter([
            'col-' . $this->size->value, 
            $this->smallDeviceSize ? ('col-sm-' . $this->smallDeviceSize->value) : null,
            $this->mediumDeviceSize ? ('col-md-' . $this->mediumDeviceSize->value) : null,
            $this->largeDeviceSize ? ('col-lg-' . $this->largeDeviceSize->value) : null,
            $this->extraLargeDeviceSize ? ('col-xl-' . $this->extraLargeDeviceSize->value) : null
        ], fn($elem) => $elem != null));
    }
}