<?php 

namespace Src\Controllers;

use GTG\MVC\Request;
use Src\Models\{ ForgotPasswordForm, ResetPasswordForm };
use Src\Models\AR\{ Config, User };
use Src\Views\LayoutFactory;
use Src\Views\Pages\ResetPasswordPage;

class ResetPasswordController extends Controller
{
    public function index(Request $request): void
    {
        $this->renderPage('reset-password', [
            'layout' => LayoutFactory::createMain(),
            'page' => new ResetPasswordPage(
                formAction: $this->router->route(
                    'resetPassword.index', 
                    $request->get('redirect') ? ['redirect' => $request->get('redirect')] : []
                ),
                redirectURL: $request->get('redirect')
            )
        ]);
    }

    public function email(Request $request): void 
    {
        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordForm->email = $request->get('email');
        $user = $forgotPasswordForm->getUser();

        $email = $this->createEmail();
        $email->add(_('Reset Your Password'), $this->getEmailHTML('reset-password', [
            'user' => $user,
            'logo' => Config::getLogoURL(),
            'redirect' => $request->get('redirect')
        ]), $user->name, $user->email);
        $email->send();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_("An email was sent to %s. Check it in order to reset your password."), $user->email)]
        ]);
    }

    public function reset(Request $request): void
    {
        if(!$user = User::getByToken($request->get('token'))) {
            $this->setErrorFlash(_('The token is invalid!'));
            $this->redirect('home.index');
        }

        $this->renderPage('reset-password', [
            'layout' => LayoutFactory::createMain(),
            'page' => new ResetPasswordPage(
                formAction: $this->router->route(
                    'resetPassword.verify', 
                    array_merge([
                        'token' => $user->token
                    ], $request->get('redirect') ? ['redirect' => $request->get('redirect')] : [])
                ),
                redirectURL: $request->get('redirect'),
                hasToken: true
            )
        ]);
    }

    public function verify(Request $request): void 
    {
        if(!$user = User::getByToken($request->get('token'))) {
            $this->writeForbiddenResponse([
                'message' => ['error', _('The token is invalid!')]
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
        $this->setSuccessFlash(_('Your password was reseted successfully!'));

        $this->writeSuccessResponse([
            'redirectURL' => $request->get('redirect') ? url($request->get('redirect')) : $this->router->route('home.index')
        ]);
    }
}