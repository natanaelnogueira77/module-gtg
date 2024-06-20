<?php 

namespace Controllers;

use GTG\MVC\Request;
use Models\AR\{ Config, User };
use Models\ConfigForm;
use Program;
use Views\Pages\AdminPage;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        echo new AdminPage(request: $request);
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