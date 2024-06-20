<?php 

namespace Views\Components\Layout;

use GTG\MVC\{ Application, Session };
use Models\AR\Config;
use Views\Components\{ Material\DropdownItem, Modals\ExpiredSessionLoginModal };
use Views\Layouts\Dashboard\{ 
    Footer, 
    Header, 
    HeaderAvatarDropdown, 
    HeaderLanguages,
    HeaderNavbar, 
    HeaderNavbarItem, 
    HeaderNotificationBell, 
    HeaderRight, 
    Left, 
    LeftNavbar, 
    LeftNavbarItem, 
    Theme 
};
use Views\Widget;

class AppTheme extends Widget 
{
    private string $style;

    public function __construct(
        private readonly string $title, 
        private readonly ?Widget $body = null, 
        private readonly ?array $styles = null, 
        private ?array $scripts = null, 
        private ?array $modals = null
    ) 
    {
        $this->style = Config::getStyle();
        $this->modals = array_merge([
            new ExpiredSessionLoginModal(id: 'login-modal')
        ], $this->modals ?? []);
        $this->scripts = array_merge([
            url('public/assets/js/Layouts/Dashboard.js') => 'module'
        ], $this->scripts ?? []);
    }

    public function __toString(): string 
    {
        return new Theme(
            title: $this->title, 
            logoUrl: Config::getLogoURL(),
            logoIconUrl: Config::getLogoIconURL(),
            backgroundImageUrl: Config::getLoginImageURL(), 
            header: $this->getHeader(),
            left: $this->getLeft(),
            body: $this->body, 
            footer: $this->getFooter(),
            styles: $this->styles, 
            scripts: $this->scripts, 
            modals: $this->modals
        );
    }

    private function getHeader(): Header 
    {
        $session = Application::getInstance()->session;
        $router = Application::getInstance()->router;
        $user = $session->getAuth();
        return new Header(
            backgroundColor: $this->style == 'dark' ? 'bg-premium-dark' : 'bg-heavy-rain',
            textColor: $this->style == 'dark' ? 'header-text-light' : 'header-text-dark',
            logoUrl: Config::getLogoURL(),
            hasLeft: $user ? true : false,
            navbar: new HeaderNavbar(
                children: [
                    new HeaderNavbarItem(
                        url: $router->route($user && $user->isAdmin() ? 'admin.index' : 'home.index'),
                        iconClass: 'icofont-home',
                        text: _('Principal')
                    )
                ]
            ),
            right: new HeaderRight(
                children: [
                    new HeaderLanguages(
                        currentImageUrl: url("public/imgs/flags/{$session->getLanguage()[1]}.png"),
                        children: [
                            new DropdownItem(
                                type: 'link',
                                url: $router->route('languages.index', ['lang' => 'pt']),
                                content: _('Português')
                            )
                        ]
                    ), 
                    new HeaderNotificationBell(
                        iconColor: $this->style == 'dark' ? 'header-text-light' : 'header-text-dark'
                    ), 
                    new HeaderAvatarDropdown(
                        avatarUrl: $user->getAvatarURL(), 
                        title: $user->name, 
                        subtitle: $user->getUserType(), 
                        children: [
                            new DropdownItem(
                                type: 'link',
                                url: $router->route('editAccount.index'),
                                content: _('Editar Minha Conta')
                            ),
                            new DropdownItem(
                                type: 'link',
                                url: $router->route('auth.logout'),
                                content: _('Sair')
                            )
                        ]
                    )
                ]
            )
        );
    }

    private function getLeft(): Left 
    {
        $session = Application::getInstance()->session;
        $router = Application::getInstance()->router;
        $user = $session->getAuth();
        $currentRoute = $router->route($router->current()->name);
        return new Left(
            backgroundColor: $this->style == 'dark' ? 'bg-premium-dark' : 'bg-heavy-rain',
            textColor: $this->style == 'dark' ? 'sidebar-text-light' : 'sidebar-text-dark',
            navbar: new LeftNavbar(
                children: $user ? array_merge($user->isAdmin() ? [
                    new LeftNavbarItem(
                        isHeading: true,
                        text: _('Administração')
                    ),
                    new LeftNavbarItem(
                        currentRoute: $currentRoute,
                        text: _('Usuários'), 
                        url: $router->route('users.index'), 
                        iconClass: 'icofont-users'
                    )
                ] : [], [
                    new LeftNavbarItem(
                        isHeading: true,
                        text: _('Controle')
                    ),
                    new LeftNavbarItem(
                        currentRoute: $currentRoute,
                        text: _('Painel Principal'), 
                        url: $router->route($user->isAdmin() ? 'admin.index' : 'user.index'), 
                        iconClass: 'icofont-dashboard-web'
                    )
                ]) : []
            )
        );
    }

    private function getFooter(): Footer 
    {
        return new Footer(
            logoIconUrl: Config::getLogoIconURL(), 
            appName: Application::getInstance()->appData['app_name'], 
            copyrightText: _('Soluções Smart')
        );
    }
}