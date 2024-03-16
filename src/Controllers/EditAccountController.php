<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Controllers\Controller;
use Src\Models\AR\User;
use Src\Views\LayoutFactory;
use Src\Views\Widgets\Sections\EditAccount as EditAccountSection;

class EditAccountController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('edit-account', [
            'layout' => LayoutFactory::createMain(),
            'editAccountSection' => new EditAccountSection(
                formId: 'filters',
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