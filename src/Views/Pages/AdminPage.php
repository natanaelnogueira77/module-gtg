<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\{ Config, User };
use Views\Components\{ 
    Layout\AppTheme,
    Material\ActionButton,
    Material\AppFooter, 
    Material\AppHeader, 
    Material\AppLeft,
    Material\Stack,
    Modals\MediaLibraryModal, 
    Sections\ConfigFormSection, 
    Sections\ApplicationInfoSection, 
    Sections\LayoutTitleSection
};
use Views\Widget;

class AdminPage extends Widget
{
    public function __construct(
        private readonly Request $request
    )
    {}

    public function __toString(): string 
    {
        return new AppTheme(
            title: sprintf(_('Administrador | %s'), Application::getInstance()->appData['app_name']), 
            body: new Stack(
                children: [
                    new LayoutTitleSection(
                        title: _('Painel do Administrador'),
                        subtitle: _('Relatórios e gerenciamento do sistema'),
                        icon: 'pe-7s-home',
                        iconColor: 'bg-malibu-beach'
                    ),
                    new ApplicationInfoSection(
                        id: 'application-info',
                        version: Application::getInstance()->appData['app_version'],
                        userTypes: User::getUserTypes(),
                        usersCount: User::getUsersCountGroupedByUserType(), 
                        actionButtons: [
                            new ActionButton(
                                color: 'success', 
                                size: 'md', 
                                content: _('Atualizar Sistema'),
                                attributes: [
                                    'id' => 'update-system',
                                    'data-action' => Application::getInstance()->router->route('admin.update'), 
                                    'data-method' => 'put', 
                                    'data-confirm-message' => _('Você tem certeza de que deseja atualizar o sistema para a última versão?')
                                ]
                            ),
                            new ActionButton(
                                color: 'danger', 
                                size: 'md', 
                                content: _('Remover Dados'),
                                attributes: [
                                    'id' => 'reset-system',
                                    'data-action' => Application::getInstance()->router->route('admin.reset'), 
                                    'data-method' => 'delete', 
                                    'data-confirm-message' => _('Você tem certeza de que deseja remover todos os dados? Essa ação não terá volta!')
                                ]
                            )
                        ]
                    ), 
                    new ConfigFormSection(
                        formId: 'update-config',
                        action: Application::getInstance()->router->route('admin.updateConfig'),
                        method: 'put', 
                        configValues: Config::getValuesByMetaKeys([
                            Config::KEY_LOGO, 
                            Config::KEY_LOGO_ICON,
                            Config::KEY_STYLE,
                            Config::KEY_LOGIN_IMAGE
                        ])
                    )
                ],
            ),
            modals: [
                new MediaLibraryModal(hasSession: true)
            ],
            scripts: [
                url('public/assets/js/Pages/AdminPage.js') => 'module'
            ]
        );
    }
}