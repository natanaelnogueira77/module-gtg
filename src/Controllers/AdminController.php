<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Controllers\Controller;
use Src\Models\AR\Config;
use Src\Models\AR\User;
use Src\Models\ConfigForm;
use Src\Views\LayoutFactory;
use Src\Views\Widgets\Sections\ApplicationInfo as ApplicationInfoSection;
use Src\Views\Widgets\Sections\SystemOptions as SystemOptionsSection;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('admin', [
            'layout' => LayoutFactory::createMain(),
            'applicationInfoSection' => new ApplicationInfoSection(
                applicationName: $this->appData['app_name'],
                applicationVersion: $this->appData['app_version'],
                userTypes: User::getUserTypes(),
                usersCount: User::getUsersCountGroupedByUserType()
            ),
            'systemOptionsSection' => new SystemOptionsSection(
                formId: 'update-config',
                formAction: $this->router->route('admin.updateConfig'),
                formMethod: 'put',
                loginImageId: 'login-image-area',
                logoId: 'logo-area',
                logoIconId: 'logo-icon-area',
                configValues: Config::getValuesByMetaKeys([
                    Config::KEY_LOGO, 
                    Config::KEY_LOGO_ICON,
                    Config::KEY_STYLE,
                    Config::KEY_LOGIN_IMAGE
                ])
            ),
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