<?php 

use Src\Views\Components\LayoutTitle;

$this->layout('layouts/main', ['layout' => $layout]);
$this->insert('components/layout-title', [
    'component' => new LayoutTitle(
        title: _('User Dashboard'),
        subtitle: _('Welcome!'),
        icon: 'icofont-user',
        iconColor: 'text-primary'
    )
]);