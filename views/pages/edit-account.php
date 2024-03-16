<?php 

use Src\Views\Components\LayoutTitle;

$this->layout('layouts/main', ['layout' => $layout]);
$this->insert('components/layout-title', [
    'component' => new LayoutTitle(
        title: _('Edit Account'),
        subtitle: _('Change the fields below in order to edit your account'),
        icon: 'icofont-edit',
        iconColor: 'text-primary'
    )
]);

$this->insert('widgets/sections/edit-account', [
    'widget' => $editAccountSection
]);

$this->start('scripts'); 
$this->insert('scripts/sections/edit-account.js', [
    'widget' => $editAccountSection
]);
$this->end();