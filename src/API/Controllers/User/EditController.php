<?php

namespace Src\API\Controllers\User;

use GTG\MVC\Controller;
use Src\Models\UserForm;
use Src\Utils\ErrorMessages;

class EditController extends Controller 
{
    public function update(array $data): void 
    {
        $user = $this->session->getAuth();
        $userForm = (new UserForm())->loadData([
            'id' => $user->id,
            'utip_id' => intval($user->utip_id),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'password_confirm' => $data['password_confirm'],
            'update_password' => $data['update_password'] ? true : false
        ]);

        if(!$userForm->validate()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $userForm->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $dbUser = $user;
        $dbUser->loadData([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['update_password'] ? $data['password'] : $user->password,
            'slug' => $data['slug']
        ]);

        if(!$dbUser->save()) {
            $this->setMessage('error', ErrorMessages::form())->setErrors(
                $dbUser->getFirstErrors()
            )->APIResponse([], 422);
            return;
        }

        $this->session->setAuth($dbUser);
        $this->setMessage(
            'success', 
            _('Seus dados foram atualizados com sucesso!')
        )->APIResponse([], 200);
        return;
    }
}