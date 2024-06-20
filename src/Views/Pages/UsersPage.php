<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\User;
use Views\Components\{ 
    Layout\AppTheme,
    Material\Stack,
    Modals\MediaLibraryModal, 
    Modals\SaveUserModal, 
    Sections\LayoutTitleSection, 
    Sections\UsersTableSection 
};
use Views\Widget;

class UsersPage extends Widget
{
    public function __construct(
        private readonly Request $request, 
    )
    {}

    public function __toString(): string 
    {
        $userTypes = User::getUserTypes();
        return new AppTheme(
            title: sprintf(_('Usuários | %s'), Application::getInstance()->appData['app_name']),
            body: new Stack(
                children: [
                    new LayoutTitleSection(
                        title: _('Lista de Usuários'),
                        subtitle: _('Segue abaixo a lista de usuários do sistema'),
                        icon: 'pe-7s-users',
                        iconColor: 'bg-malibu-beach'
                    ),
                    new UsersTableSection(
                        tableId: 'users',
                        filtersFormId: 'filters',
                        formId: 'save-user-form',
                        modalId: 'save-user-modal',
                        actionButtonId: 'create-user',
                        action: Application::getInstance()->router->route('users.list'), 
                        storeAction: Application::getInstance()->router->route('users.store'),
                        userTypes: $userTypes
                    ),
                ],
            ),
            modals: [
                new MediaLibraryModal(hasSession: true), 
                new SaveUserModal(
                    id: 'save-user-modal', 
                    formId: 'save-user-form', 
                    userTypes: $userTypes
                )
            ],
            scripts: [
                url('public/assets/js/Pages/UsersPage.js') => 'module'
            ]
        );
    }
}