<?php 

namespace Controllers;

use GTG\MVC\Request;
use Models\AR\User;
use Utils\ThemeUtils;
use Views\Pages\EditAccountPage;

class EditAccountController extends Controller
{
    public function index(Request $request): void
    {
        echo new EditAccountPage(request: $request);
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