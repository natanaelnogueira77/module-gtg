<?php 

use Src\Views\Components\LayoutTitle;
use Src\Views\Widgets\Sections\UsersList as UsersListSection;

$usersListSection = new UsersListSection(
    formId: 'save-user-form',
    buttonId: 'create-user',
    tableId: 'users',
    modalId: 'save-user-modal',
    filtersFormId: 'users-filters-form',
    userTypes: $page->getUserTypes()
);

$this->layout('layouts/main', ['layout' => $layout]);
$this->insert('components/layout-title', [
    'component' => new LayoutTitle(
        title: _('Users List'),
        subtitle: _('Down below is the list of the users of the system'),
        icon: 'icofont-users',
        iconColor: 'text-primary'
    )
]);

$this->insert('widgets/sections/users-list', [
    'widget' => $usersListSection
]);

$this->start('modals');
$this->insert('modals/save-user', [
    'formId' => $usersListSection->getFormId(),
    'modalId' => $usersListSection->getModalId(),
    'userTypes' => $usersListSection->getUserTypes()
]);
$this->end(); 

$this->start('scripts'); 
$this->insert('scripts/sections/users-list.js', [
    'widget' => $usersListSection
]);
$this->end();