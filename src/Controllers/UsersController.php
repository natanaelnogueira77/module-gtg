<?php 

namespace Controllers;

use GTG\MVC\{ Request, Utils\Email };
use Exceptions\ApplicationException;
use Models\{ AR\User, Lists\UsersList };
use Utils\ThemeUtils;
use Views\{
    Components\DataTables\UsersDataTable,
    Components\Emails\RegisteredUserEmail, 
    Pages\UsersPage
};

class UsersController extends Controller
{
    public function index(Request $request): void
    {
        echo new UsersPage(request: $request);
    }

    public function show(Request $request): void 
    {
        $model = $this->getUser($request);
        $this->writeSuccessResponse([
            'data' => array_replace($model->columnsValuesToArray(), [
                'password' => null,
                'avatarImageURI' => $model->getAvatarImageURI(),
                'avatarImageURL' => $model->getAvatarImageURL()
            ]),
            'update' => [
                'action' => $this->router->route('users.update', ['user_id' => $model->id]), 
                'method' => 'put'
            ]
        ]);
    }

    private function getUser(Request $request): User 
    {
        if(!$model = User::getById(intval($request->get('user_id')))) {
            throw new ApplicationException(_('Nenhum usuário foi encontrado!'), 404);
        }

        return $model;
    }

    public function store(Request $request): void 
    {
        $model = (new User())->fillAttributes([
            'user_type' => $request->get('user_type'), 
            'name' => $request->get('name'), 
            'email' => $request->get('email'), 
            'password' => $request->get('password'),
            'avatar_image' => $request->get('avatar_image')
        ]);
        $model->validate();
        $model->save();

        try {
            $email = new Email();
            $email->add(_('Você foi registrado com sucesso!'), new RegisteredUserEmail(
                user: $model, 
                password: $request->get('password')
            ), $model->name, $model->email);
            $email->send();
        } catch(Exception $e) {}

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('O usuário "%s" foi criado com sucesso!'), $model->name)], 
            'data' => array_replace($model->columnsValuesToArray(), ['password' => null])
        ]);
    }

    public function update(Request $request): void 
    {
        $model = $this->getUser($request);
        $model->fillAttributes([
            'user_type' => $request->get('user_type'), 
            'name' => $request->get('name'), 
            'email' => $request->get('email'), 
            'password' => $request->get('update_password') 
                ? ($request->get('password') ? $request->get('password') : null) 
                : $model->password,
            'avatar_image' => $request->get('avatar_image')
        ]);
        $model->validate();
        $model->save();

        $this->writeSuccessResponse(['message' => ['success', sprintf(_('O usuário "%s" foi atualizado com sucesso!'), $model->name)]]);
    }

    public function delete(Request $request): void 
    {
        $model = $this->getUser($request);
        $model->destroy();
        $this->writeSuccessResponse(['message' => ['success', sprintf(_('O usuário "%s" foi excluído com sucesso!'), $model->name)]]);
    }

    public function list(Request $request): void 
    {
        $list = new UsersList(
            limit: $request->get('limit') ? intval($request->get('limit')) : 10,
            pageToShow: $request->get('page') ? intval($request->get('page')) : 1,
            orderBy: $request->get('orderBy') ? $request->get('orderBy') : 'id',
            orderType: $request->get('orderType') ? $request->get('orderType') : 'ASC',
            searchTerm: $request->get('search') ? $request->get('search') : null,
            userType: $request->get('userType') ? intval($request->get('userType')) : null
        );
        $models = User::getList($list);
        $list->setResultsCount(User::getListResultsCount($list));
        
        echo new UsersDataTable(
            models: $models, 
            list: $list
        );
    }
}