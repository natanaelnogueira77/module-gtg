<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\AR\User;
use Src\Views\LayoutFactory;
use Src\Views\Pages\EditAccountPage;

class EditAccountController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('edit-account', [
            'layout' => LayoutFactory::createMain(
                sprintf(_('Edit Account | %s'), $this->appData['app_name'])
            ),
            'page' => new EditAccountPage(
                user: $this->session->getAuth(),
                userTypes: User::getUserTypes()
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
            'token' => md5($request->get('email'))
        ]);
        $user->validate();
        $user->save();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('Your data was updated successfully, %s!'), $user->name)]
        ]);
    }
}