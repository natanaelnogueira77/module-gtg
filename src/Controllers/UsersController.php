<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Exceptions\ApplicationException;
use Src\Models\AR\{ Config, User };
use Src\Models\Lists\UsersList;
use Src\Views\LayoutFactory;
use Src\Views\Pages\UsersPage;

class UsersController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('users', [
            'layout' => LayoutFactory::createMain(),
            'page' => new UsersPage(
                userTypes: User::getUserTypes()
            )
        ]);
    }

    public function show(Request $request): void 
    {
        $user = $this->getUser($request);
        $this->writeSuccessResponse([
            'data' => array_replace($user->columnsValuesToArray(), ['password' => null]),
            'update' => [
                'action' => $this->router->route('users.update', ['user_id' => $user->id]), 
                'method' => 'put'
            ]
        ]);
    }

    private function getUser(Request $request): User 
    {
        if(!$user = User::findById(intval($request->get('user_id')))) {
            throw new ApplicationException(_('No user was found!'), 404);
        }

        return $user;
    }

    public function store(Request $request): void 
    {
        $user = (new User())->fillAttributes([
            'user_type' => $request->get('user_type'), 
            'name' => $request->get('name'), 
            'email' => $request->get('email'), 
            'password' => $request->get('password')
        ]);
        $user->validate();
        $user->save();

        $email = $this->createEmail();
        $email->add(_('You Were Successfully Registered!'), $this->getEmailHTML('registered-user', [
            'user' => $user,
            'logo' => Config::getLogoURL(),
            'password' => $request->get('password')
        ]), $user->name, $user->email);
        $email->send();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('The user "%s" was successfully created!'), $user->name)]
        ]);
    }

    public function update(Request $request): void 
    {
        $user = $this->getUser($request);
        $user->fillAttributes([
            'user_type' => $request->get('user_type'), 
            'name' => $request->get('name'), 
            'email' => $request->get('email'), 
            'password' => $request->get('update_password') 
                ? ($request->get('password') ? $request->get('password') : null) 
                : $user->password
        ]);
        $user->validate();
        $user->save();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('The user "%s" was successfully updated!'), $user->name)]
        ]);
    }

    public function delete(Request $request): void 
    {
        $user = $this->getUser($request);
        $user->destroy();
        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('The user "%s" was successfully deleted.'), $user->name)]
        ]);
    }

    public function list(Request $request): void 
    {
        $usersList = new UsersList(
            limit: $request->get('limit') ? intval($request->get('limit')) : 10,
            pageToShow: $request->get('page') ? intval($request->get('page')) : 1,
            orderBy: $request->get('orderBy') ? $request->get('orderBy') : 'id',
            orderType: $request->get('orderType') ? $request->get('orderType') : 'ASC',
            searchTerm: $request->get('search') ? $request->get('search') : null,
            userType: $request->get('userType') ? intval($request->get('userType')) : null
        );
        $models = User::getList($usersList);
        $usersList->setResultsCount(User::getListResultsCount($usersList));
        
        $this->writeSuccessResponse([
            'html' => $this->getWidgetHTML('data-tables/users', [
                'models' => $models,
                'usersList' => $usersList
            ])
        ]);
    }
}