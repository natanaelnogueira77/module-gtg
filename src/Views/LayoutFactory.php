<?php 

namespace Src\Views;

use GTG\MVC\Application;
use Src\Models\AR\{ Config, Notification };
use Src\Views\Components\DropdownMenuItem;
use Src\Views\Components\MainLayout\{ 
    Footer,
    Header,
    HeaderAvatarDropdown,
    HeaderLanguages,
    HeaderMenu,
    HeaderMenuItem,
    HeaderNotificationBell,
    HeaderRight,
    Language,
    Left,
    LeftMenu,
    LeftMenuItem,
    Social
};
use Src\Views\Layouts\Main as MainLayout;

class LayoutFactory 
{
    public static function createMain(string $title): MainLayout 
    {
        $configValues = Config::getValuesByMetaKeys([
            Config::KEY_LOGIN_IMAGE, 
            Config::KEY_LOGO, 
            Config::KEY_LOGO_ICON, 
            Config::KEY_STYLE
        ]);
        $router = Application::getInstance()->router;
        $user = Application::getInstance()->session->getAuth();
        $language = Application::getInstance()->session->getLanguage();
        if($user) {
            $notifications = Notification::getUnreadByUser($user);
        }

        return new MainLayout(
            applicationName: Application::getInstance()->appData['app_name'],
            applicationVersion: Application::getInstance()->appData['app_version'],
            logoURL: $configValues[Config::KEY_LOGO]['url'],
            logoIconURL: $configValues[Config::KEY_LOGO_ICON]['url'],
            title: $title,
            header: new Header(
                backgroundColor: self::getBackgroundColorByTheme($configValues[Config::KEY_STYLE]),
                textColor: static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                logoURL: $configValues[Config::KEY_LOGO]['url'],
                menu: new HeaderMenu(
                    items: [
                        new HeaderMenuItem(
                            url: $router->route('home.index'),
                            text: _('Home'),
                            textColor: 'text-' . static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                            icon: 'icofont-home'
                        )
                    ]
                ), 
                right: new HeaderRight(
                    isLogged: $user ? true : false,
                    avatarDropdown: $user ? new HeaderAvatarDropdown(
                        avatarLinkColor: static::getAvatarLinkColorByTheme($configValues[Config::KEY_STYLE]),
                        avatarImageUrl: $user->getAvatarURL(),
                        items: [
                            new DropdownMenuItem(
                                type: DropdownMenuItem::LINK_TYPE,
                                url: $router->route('editAccount.index'),
                                text: _('Edit my Account')
                            ),
                            new DropdownMenuItem(
                                type: DropdownMenuItem::LINK_TYPE,
                                url: $router->route('auth.logout'),
                                text: _('Logout'),
                                hasDivider: true
                            )
                        ]
                    ) : null,
                    languages: new HeaderLanguages(
                        languages: [
                            new Language(
                                id: 'en_US',
                                url: $router->route('language.index', ['lang' => 'en']),
                                imageURL: url('public/imgs/flags/en_US.png'),
                                name: _('English')
                            ),
                            new Language(
                                id: 'es_ES',
                                url: $router->route('language.index', ['lang' => 'es']),
                                imageURL: url('public/imgs/flags/es_ES.png'),
                                name: _('Spanish')
                            ),
                            new Language(
                                id: 'pt_BR',
                                url: $router->route('language.index', ['lang' => 'pt']),
                                imageURL: url('public/imgs/flags/pt_BR.png'),
                                name: _('Portuguese')
                            )
                        ],
                        currentLanguage: $language[1]
                    ),
                    notificationBell: $user ? new HeaderNotificationBell(
                        iconColor: 'text-' . static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                        notifications: $notifications
                    ) : null
                ),
                hasLeft: $user ? true : false
            ),
            left: $user ? new Left(
                backgroundColor: static::getBackgroundColorByTheme($configValues[Config::KEY_STYLE]),
                textColor: static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                menu: new LeftMenu(
                    currentURL: url() . $router->current()->route,
                    items: array_merge([
                        new LeftMenuItem(
                            url: $router->route($user->isAdmin() ? 'admin.index' : 'user.index'),
                            text: _('Main Dashboard'),
                            textColor: 'text-' . static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                            icon: 'icofont-dashboard-web'
                        )
                    ], $user->isAdmin() ? [
                        new LeftMenuItem(
                            url: $router->route('users.index'),
                            text: _('Users List'),
                            textColor: 'text-' . static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                            icon: 'icofont-users'
                        )
                    ] : [])
                )
            ) : null,
            footer: new Footer(
                backgroundColor: static::getBackgroundColorByTheme($configValues[Config::KEY_STYLE]),
                textColor: static::getTextColorByTheme($configValues[Config::KEY_STYLE]),
                logoURL: $configValues[Config::KEY_LOGO]['url'],
                copyrightText: _('Smart Solutions'),
                socials: [
                    new Social(
                        buttonColor: 'info',
                        url: '#',
                        icon: 'icofont-facebook'
                    ),
                    new Social(
                        buttonColor: 'info',
                        url: '#',
                        icon: 'icofont-instagram'
                    ),
                    new Social(
                        buttonColor: 'info',
                        url: '#',
                        icon: 'icofont-twitter'
                    )
                ]
            )
        );
    }

    private static function getBackgroundColorByTheme(string $themeColor): string 
    {
        return $themeColor;
    }

    private static function getTextColorByTheme(string $themeColor): string 
    {
        if($themeColor == 'light') {
            return 'dark';
        } elseif($themeColor == 'dark') {
            return 'white';
        }
        return 'dark';
    }

    private static function getAvatarLinkColorByTheme(string $themeColor): string 
    {
        if($themeColor == 'light') {
            return 'dark';
        } elseif($themeColor == 'dark') {
            return 'white';
        }
        return 'dark';
    }
}