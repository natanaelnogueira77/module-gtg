<?php 

use Src\Views\Components\LayoutTitle;
use Src\Views\Widgets\Sections\{ 
    ApplicationInfo as ApplicationInfoSection,
    SystemOptions as SystemOptionsSection
};

$systemOptionsSection = new SystemOptionsSection(
    formId: 'update-config',
    formAction: $router->route('admin.updateConfig'),
    formMethod: 'put',
    loginImageId: 'login-image-area',
    logoId: 'logo-area',
    logoIconId: 'logo-icon-area',
    configValues: $page->getConfigValues()
);

$this->layout("layouts/main", ['layout' => $layout]);
$this->insert('components/layout-title', [
    'component' => new LayoutTitle(
        title: _('Admin Dashboard'),
        subtitle: _('Reports and system management'),
        icon: 'icofont-home',
        iconColor: 'text-primary'
    )
]);

$this->insert('widgets/sections/application-info', [
    'widget' => new ApplicationInfoSection(
        applicationName: $appData['app_name'],
        applicationVersion: $appData['app_version'],
        userTypes: $page->getUserTypes(),
        usersCount: $page->getUsersCount()
    )
]);

$this->insert('widgets/sections/system-options', ['widget' => $systemOptionsSection]);

$this->start('modals'); 
$this->insert('components/media-library/modal');
$this->end();

$this->start('scripts'); 
$this->insert('scripts/sections/system-options.js', ['widget' => $systemOptionsSection]);
$this->end();