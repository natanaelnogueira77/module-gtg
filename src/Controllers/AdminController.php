<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\{ Config, User };
use Src\Models\ConfigForm;
use Src\Program;
use Src\Utils\ThemeUtils;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('admin', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Administrador | %s'), $this->appData['app_name'])
            ),
            'userTypes' => User::getUserTypes(),
            'usersCount' => User::getUsersCountGroupedByUserType(),
            'configValues' => Config::getValuesByMetaKeys([
                Config::KEY_LOGO, 
                Config::KEY_LOGO_ICON,
                Config::KEY_STYLE,
                Config::KEY_LOGIN_IMAGE
            ])
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

        $this->setSuccessFlash(_('As configurações foram atualizadas com sucesso!'));
        $this->writeSuccessResponse();
    }

    public function update(Request $request): void 
    {
        Program::applyMigrations();
        $this->writeSuccessResponse(['message' => ['success', _('A plataforma foi atualizada para a última versão com sucesso!')]]);
    }

    public function reset(Request $request): void 
    {
        Program::reverseMigrations();
        Program::applyMigrations();
        Program::applySeeders();

        $this->writeSuccessResponse(['message' => ['success' => _('A plataforma foi resetada com sucesso.')]]);
    }
}