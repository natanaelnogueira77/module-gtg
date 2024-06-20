<?php 

namespace Controllers;

use GTG\MVC\{ Request, Utils\Email };
use Models\{ AR\Config, AR\User, ForgotPasswordForm, ResetPasswordForm };
use Views\{ Components\Emails\ResetPasswordEmail, Pages\ResetPasswordPage };

class ResetPasswordController extends Controller
{
    public function index(Request $request): void
    {
        echo new ResetPasswordPage(request: $request);
    }

    public function email(Request $request): void 
    {
        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordForm->email = $request->get('email');
        $user = $forgotPasswordForm->getUser();

        $email = new Email();
        $email->add(_('Redefina Sua Senha'), new ResetPasswordEmail(
            user: $user, 
            siteUrl: url(),
            redirectUrl: $request->get('redirect')
        ), $user->name, $user->email);
        $email->send();

        $this->writeSuccessResponse([
            'message' => ['success', sprintf(_('Um email foi enviado para %s. Verifique para redefinir sua senha.'), $user->email)]
        ]);
    }

    public function reset(Request $request): void
    {
        if(!$user = User::getByToken($request->get('token'))) {
            $this->setErrorFlash(_('A chave de verificação é inválida!'));
            $this->redirect('home.index');
        }

        echo new ResetPasswordPage(
            appData: $this->appData,
            router: $this->router, 
            request: $request, 
            token: $user->token
        );
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