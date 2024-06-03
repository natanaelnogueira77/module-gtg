<?php 

namespace Src\Utils;

use GTG\MVC\{ Router, Session };
use Src\Models\AR\{ Church, Config, User };

final class ThemeUtils 
{
    private function __construct(
        private string $title,
        private string $logoURL,
        private string $logoIconURL,
        private string $backgroundImageURL,
        private string $style, 
        private bool $isLogged = false,
        private ?array $header = null,
        private ?array $left = null,
        private ?array $footer = null
    ) 
    {}

    public function getTitle(): string 
    {
        return $this->title;
    }

    public function getLogoURL(): string 
    {
        return $this->logoURL;
    }

    public function getLogoIconURL(): string 
    {
        return $this->logoIconURL;
    }

    public function getBackgroundImageURL(): string 
    {
        return $this->backgroundImageURL;
    }

    public function getStyle(): string 
    {
        return $this->style;
    }

    public function hasHeader(): bool 
    {
        return $this->header ? true : false;
    }

    public function getHeader(): ?array 
    {
        return $this->header;
    }

    public function hasLeft(): bool 
    {
        return $this->left ? true : false;
    }

    public function getLeft(): ?array 
    {
        return $this->left;
    }

    public function hasFooter(): bool 
    {
        return $this->footer ? true : false;
    }

    public function getFooter(): ?array 
    {
        return $this->footer;
    }

    public function isLogged(): bool 
    {
        return $this->isLogged;
    }

    public static function createDefault(Router $router, Session $session, string $title): self 
    {
        $user = $session->getAuth();
        return new self(
            title: $title,
            logoURL: Config::getLogoURL(),
            logoIconURL: Config::getLogoIconURL(),
            backgroundImageURL: Config::getLoginImageURL(),
            style: Config::getStyle(), 
            isLogged: $user ? true : false,
            header: [
                'backgroundColor' => self::getHeaderBackgroundColor(),
                'textColor' => self::getHeaderTextColor(),
                'hasLeft' => $user ? true : false,
                'logoURL' => Config::getLogoURL(),
                'isLogged' => $user ? true : false,
                'nav' => ['items' => self::getHeaderItems($router, $session)],
                'right' => [
                    'languages' => self::getLanguages($router, $session),
                    'isLogged' => $user ? true : false,
                    'loginRoute' => $router->route('auth.index'),
                    'notificationsBell' => $user ? ['iconColor' => self::getHeaderTextColor()] : null,
                    'avatar' => $user ? [
                        'imageURL' => $user->getAvatarURL(),
                        'title' => $user->name,
                        'subtitle' => $user->getUserType(),
                        'items' => self::getAvatarDropdownItems($router)
                    ] : null
                ]
            ], 
            left: $user ? [
                'backgroundColor' => self::getLeftBackgroundColor(),
                'textColor' => self::getLeftTextColor(),
                'nav' => ['items' => self::getLeftItems($router, $session)]
            ] : null,
            footer: [
                'logoIconURL' => Config::getLogoIconURL(),
                'backgroundColorURL' => self::getFooterBackgroundColor(),
                'textColor' => self::getFooterTextColor(),
                'copyrightText' => _('GTG Software')
            ]
        );
    }

    private static function getThemeOptions(): array 
    {
        return [
            'logoURL' => Config::getLogoURL(),
            'logoIconURL' => Config::getLogoIconURL(), 
            'backgroundImageURL' => Config::getLoginImageURL(), 
            'style' => Config::getStyle()
        ];
    }

    private static function getHeaderBackgroundColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'bg-heavy-rain';
            case 'dark': 
                return 'bg-premium-dark';
            default: 
                return 'bg-heavy-rain';
        }
    }

    private static function getHeaderTextColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'header-text-dark';
            case 'dark': 
                return 'header-text-light';
            default: 
                return 'header-text-dark';
        }
    }

    private static function getHeaderItems(Router $router, Session $session): array 
    {
        $user = $session->getAuth();
        $textColor = self::getHeaderTextColor();
        return [
            [
                'url' => $router->route($user ? (
                    $user->isAdmin() ? 'admin.index' : 'user.index'
                ) : 'home.index'), 
                'textColor' => $textColor,
                'iconClass' => 'icofont-home', 
                'text' => _('Principal')
            ]
        ];
    }

    private static function getLanguages(Router $router, Session $session): array 
    {
        return [
            'currentImageURL' => url("public/imgs/flags/{$session->getLanguage()[1]}.png"),
            'list' => [
                ['url' => $router->route('languages.index', ['lang' => 'pt']), 'name' => _('Português')]
            ]
        ];
    }

    private static function getLeftBackgroundColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'bg-heavy-rain';
            case 'dark': 
                return 'bg-premium-dark';
            default: 
                return 'bg-heavy-rain';
        }
    }

    private static function getLeftTextColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'sidebar-text-dark';
            case 'dark': 
                return 'sidebar-text-light';
            default: 
                return 'sidebar-text-dark';
        }
    }

    private static function getLeftItems(Router $router, Session $session): array 
    {
        $user = $session->getAuth();
        $textColor = self::getLeftTextColor();
        return $user ? array_merge($user->isAdmin() ? [
            [
                'isHeading' => true, 
                'text' => _('Administração'), 
                'textColor' => $textColor
            ],
            [
                'url' => $router->route('users.index'), 
                'textColor' => $textColor,
                'iconClass' => 'icofont-users', 
                'text' => _('Usuários')
            ]
        ] : [], [
            [
                'isHeading' => true, 
                'text' => _('Controle'), 
                'textColor' => $textColor
            ],
            [
                'url' => $router->route($user->isAdmin() ? 'admin.index' : 'user.index'), 
                'textColor' => $textColor,
                'iconClass' => 'icofont-dashboard-web', 
                'text' => _('Painel Principal')
            ]
        ]) : [];
    }

    private static function getAvatarDropdownItems(Router $router): array 
    {
        return [
            [
                'type' => 'link',
                'url' => $router->route('editAccount.index'),
                'content' => _('Editar Minha Conta')
            ],
            [
                'type' => 'link',
                'url' => $router->route('auth.logout'),
                'content' => _('Sair')
            ]
        ];
    }

    private static function getFooterBackgroundColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'bg-light';
            case 'dark': 
                return 'bg-dark';
            default: 
                return 'bg-light';
        }
    }

    private static function getFooterTextColor(): string 
    {
        switch(Config::getStyle()) {
            case 'light': 
                return 'text-dark';
            case 'dark': 
                return 'text-light';
            default: 
                return 'text-dark';
        }
    }
}