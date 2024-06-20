<?php 

namespace Views\Pages;

use GTG\MVC\{ Application, Request };
use Models\AR\User;
use Views\Components\{ 
    Layout\AppTheme, 
    Material\Stack,
    Sections\LayoutTitleSection
};
use Views\Widget;

class UserPage extends Widget
{
    public function __construct(
        private readonly Request $request
    )
    {}

    public function __toString(): string 
    {
        return new AppTheme(
            title: sprintf(_('Painel Principal | %s'), Application::getInstance()->appData['app_name']),
            body: new Stack(
                children: [
                    new LayoutTitleSection(
                        title: _('Painel Principal'),
                        subtitle: sprintf(_('Seja bem-vindo(a), %s!'), Application::getInstance()->session->getAuth()->name),
                        icon: 'pe-7s-home',
                        iconColor: 'bg-malibu-beach'
                    )
                ],
            ),
            scripts: [
                url('public/assets/js/Pages/UserPage.js') => 'module'
            ]
        );
    }
}