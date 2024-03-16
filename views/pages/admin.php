<?php 

use Src\Views\Components\LayoutTitle;

$this->layout("layouts/main", ['layout' => $layout]);
$this->insert('components/layout-title', [
    'component' => new LayoutTitle(
        title: _('Admin Dashboard'),
        subtitle: _('Reports and system management'),
        icon: 'icofont-home',
        iconColor: 'text-primary'
    )
]);

$this->insert('widgets/sections/application-info', ['widget' => $applicationInfoSection]);
$this->insert('widgets/sections/system-options', ['widget' => $systemOptionsSection]);

$this->start('modals'); 
$this->insert('components/media-library/modal');
$this->end();

$this->start('scripts'); 
$this->insert('scripts/sections/system-options.js', ['widget' => $systemOptionsSection]);
$this->end();