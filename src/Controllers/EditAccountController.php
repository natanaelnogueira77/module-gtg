<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\User;
use Src\Utils\ThemeUtils;

class EditAccountController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('edit-account', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Editar Conta | %s'), $this->appData['app_name'])
            )
        ]);
    }

    public function update(Request $request): void 
    {
        $user = $this->session->getAuth();
        $user->fillAttributes([
            'name' => $request->get('name'), 
            'email' => $request->get('email'), 
            'password' => $request->get('update_password') 
                ? ($request->get('password') ? $request->get('password') : null) 
                : $user->password,
            'avatar_image' => $request->get('avatar_image')
        ]);
        $user->validate();
        $user->save();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('Seus dados foram atualizados com sucesso, %s!'), $user->name)]
        ]);
    }
}