<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\User;
use Views\Components\{ 
    Layout\AppTheme,
    Material\Stack,
    Modals\MediaLibraryModal,
    Sections\EditAccountSection, 
    Sections\LayoutTitleSection
};
use Views\Widget;

class EditAccountPage extends Widget
{
    public function __construct(
        private readonly Request $request
    )
    {}

    public function __toString(): string 
    {
        return new AppTheme(
            title: sprintf(_('Editar Conta | %s'), Application::getInstance()->appData['app_name']),
            body: new Stack(
                children: [
                    new LayoutTitleSection(
                        title: _('Editar Conta'),
                        subtitle: _('Edite os detalhes de sua conta logo abaixo'),
                        icon: 'pe-7s-user',
                        iconColor: 'bg-malibu-beach'
                    ), 
                    new EditAccountSection(
                        formId: 'save-user-form',
                        action: Application::getInstance()->router->route('editAccount.update'),
                        method: 'put',
                        returnUrl: Application::getInstance()->router->route('user.index'), 
                        userTypes: User::getUserTypes(), 
                        user: Application::getInstance()->session->getAuth()
                    )
                ],
            ),
            modals: [
                new MediaLibraryModal(hasSession: true)
            ],
            scripts: [
                url('public/assets/js/Pages/EditAccountPage.js') => 'module'
            ]
        );
    }
}