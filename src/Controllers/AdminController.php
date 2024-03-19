<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\{ Config, User };
use Src\Models\ConfigForm;
use Src\Views\LayoutFactory;
use Src\Views\Pages\AdminPage;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('admin', [
            'layout' => LayoutFactory::createMain(),
            'page' => new AdminPage(
                userTypes: User::getUserTypes(),
                usersCount: User::getUsersCountGroupedByUserType(),
                configValues: Config::getValuesByMetaKeys([
                    Config::KEY_LOGO, 
                    Config::KEY_LOGO_ICON,
                    Config::KEY_STYLE,
                    Config::KEY_LOGIN_IMAGE
                ])
            )
        ]);
    }

    public function updateConfig(Request $request): void 
    {
        $configForm = new ConfigForm(
            logoURI: $request->get('logoURI'),
            logoIconURI: $request->get('logoIconURI'),
            loginImageURI: $request->get('loginImageURI'),
            style: $request->get('style')
        );
        $configForm->validate();

        Config::saveSystemOptions([
            Config::KEY_LOGO => $configForm->logoURI,
            Config::KEY_LOGO_ICON => $configForm->logoIconURI,
            Config::KEY_LOGIN_IMAGE => $configForm->loginImageURI,
            Config::KEY_STYLE => $configForm->style
        ]);

        $this->setSuccessFlash(_('The configurations were successfully updated!'));
        $this->writeSuccessResponse();
    }
}