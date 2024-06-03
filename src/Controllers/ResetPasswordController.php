<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\{ ForgotPasswordForm, ResetPasswordForm };
use Src\Models\AR\{ Config, User };
use Src\Utils\ThemeUtils;

class ResetPasswordController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('reset-password', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Redefinir Senha | %s'), $this->appData['app_name'])
            ),
            'formAction' => $this->router->route(
                'resetPassword.index', 
                $request->get('redirect') ? ['redirect' => $request->get('redirect')] : []
            ),
            'redirectURL' => $request->get('redirect')
        ]);
    }

    public function email(Request $request): void 
    {
        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordForm->email = $request->get('email');
        $user = $forgotPasswordForm->getUser();

        $email = $this->createEmail();
        $email->add(_('Redefina Sua Senha'), $this->getEmailHTML('reset-password', [
            'user' => $user,
            'redirect' => $request->get('redirect')
        ]), $user->name, $user->email);
        $email->send();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_("Um email foi enviado para %s. Verifique para redefinir sua senha."), $user->email)]
        ]);
    }

    public function reset(Request $request): void
    {
        if(!$user = User::getByToken($request->get('token'))) {
            $this->setErrorFlash(_('A chave de verificação é inválida!'));
            $this->redirect('home.index');
        }

        $this->renderPage('reset-password', [
            'theme' => ThemeUtils::createDefault(
                $this->router, 
                $this->session, 
                sprintf(_('Redefinir Senha | %s'), $this->appData['app_name'])
            ),
            'formAction' => $this->router->route(
                'resetPassword.verify', 
                array_merge([
                    'token' => $user->token
                ], $request->get('redirect') ? ['redirect' => $request->get('redirect')] : [])
            ),
            'redirectURL' => $request->get('redirect'),
            'hasToken' => true
        ]);
    }

    public function verify(Request $request): void 
    {
        if(!$user = User::getByToken($request->get('token'))) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('A chave de verificação é inválida!')]
            ]);
            return;
        }

        $resetPasswordForm = new ResetPasswordForm();
        $resetPasswordForm->password = $request->get('password');
        $resetPasswordForm->passwordConfirm = $request->get('passwordConfirm');
        $resetPasswordForm->validate();

        $user->password = $resetPasswordForm->password;
        $user->save();

        $this->session->setAuth($user);
        $this->setSuccessFlash(_('Sua senha foi redefinida com sucesso!'));

        $this->writeSuccessResponse([
            'redirectURL' => $request->get('redirect') ? url($request->get('redirect')) : $this->router->route('home.index')
        ]);
    }
}