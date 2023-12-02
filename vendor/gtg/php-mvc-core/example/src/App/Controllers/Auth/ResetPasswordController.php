<?php

namespace GTG\MVC\Example\Src\App\Controllers\Auth;

use GTG\MVC\Components\Email;
use GTG\MVC\Controller;
use GTG\MVC\Example\Src\Models\ForgotPasswordForm;
use GTG\MVC\Example\Src\Models\ResetPasswordForm;
use GTG\MVC\Example\Src\Models\User;

class ResetPasswordController extends Controller 
{
    public function index(array $data): void 
    {
        $forgotPasswordForm = new ForgotPasswordForm();
        if($this->request->isPost()) {
            $forgotPasswordForm->loadData(['email' => $data['email']]);
            if($user = $forgotPasswordForm->user()) {
                $email = new Email();
                $email->add(
                    _('Redefinir Senha'), 
                    $this->getView('emails/reset-password', [
                        'user' => $user
                    ]), 
                    $user->name, 
                    $user->email
                );
                
                if(!$email->send()) {
                    $this->session->setFlash('error', $email->error()->getMessage());
                }

                $this->session->setFlash(
                    'success', 
                    sprintf(
                        'An email was sent to %s. Verify in order to reset your password.',
                        $user->email
                    )
                );
                $this->redirect('auth.index');
            } else {
                $this->session->setFlash('error', 'Validation errors! Check the fields.');
            }
        }

        $this->render('auth/reset-password', [
            'forgotPasswordForm' => $forgotPasswordForm
        ]);
    }

    public function verify(array $data): void 
    {
        if(!$user = User::getByToken($data['code'])) {
            $this->session->setFlash('error', 'The token is invalid!');
            $this->redirect('auth.index');
        }
        
        $resetPasswordForm = new ResetPasswordForm();
        if($this->request->isPost()) {
            $resetPasswordForm->loadData([
                'password' => $data['password'],
                'password_confirm' => $data['password_confirm']
            ]);

            if($resetPasswordForm->validate()) {
                $user->password = $this->password;
                $user->save();

                $this->session->setAuth($user);
                $this->session->setFlash('success', 'The password was reseted successfully!');
                $this->redirect('auth.index');
            } else {
                $this->session->setFlash('error', 'Validation errors! Check the fields.');
            }
        }

        $this->render('auth/reset-password', [
            'code' => $data['code'],
            'resetPasswordForm' => $resetPasswordForm
        ]);
    }
}