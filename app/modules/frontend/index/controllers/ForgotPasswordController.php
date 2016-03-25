<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Index\Controllers;

use Core\Models\Users;
use Core\Email;
use Core\FrontController;

class ForgotPasswordController extends FrontController
{
    public function indexAction()
    {
        if($this->isLogin()) {
            $this->response->redirect('/');
            return;
        }
		
        if($this->request->isPost()) {
            $email = $this->request->getPost('email', null, '');
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                /**
                 * @var Users $user
                 */
                $user = Users::findFirst([
                    'conditions' => 'email = ?0',
                    'bind' => [$email]
                ]);
                if($user) {
                    $user->reset_password_token = randomString(255) . md5($user->email) . time();
                    $user->reset_password_token_at = date('Y-m-d H:i:s');
                    $user->save();
                    Email::getInstance()
                        ->addTo($user->email, $user->display_name)
                        ->setSubject(__('Forgot your password'))
                        ->setTemplate('index', 'forgot_password', [
                            'token' => $user->reset_password_token,
                        ])->send();
                    $this->flashSession->success(__('Please check your email to retrieve password'));
                    $this->response->redirect('/user/login/');
                    return;
                } else {
                    $this->flashSession->error('Email not found');
                }
            } else {
                $this->flashSession->error('Email invalid');
                return;
            }
        }
    }

    /**
     * Reset password form
     */
    public function resetPasswordAction()
    {
        if($this->isLogin()) {
            $this->response->redirect('/');
            return;
        }
        $user = $this->checkToken();
        if(!$user) {
            $this->flashSession->error(__('Token reset password error, please try reset password again'));
            $this->response->redirect('/user/login/');
            return;
        }

        if($this->request->isPost()) {
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');

            if(strlen($password) < 6) {
                $this->flashSession->error('Password must than 6 characters');
                return;
            }

            if(strlen($password) > 32) {
                $this->flashSession->error('Password must less than 32 characters');
                return;
            }

            if($password != $confirmPassword) {
                $this->flashSession->error('Password confirmation invalid');
                return;
            } else {
                $user->reset_password_token_at = null;
                $user->reset_password_token = null;
                $user->generatePassword($password);
                Email::getInstance()
                    ->addTo($user->email, $user->display_name)
                    ->setSubject(__('Your password changed'))
                    ->setTemplate('index', 'reset_password_success', [
                        'email' => $user->email,
                        'display_name' => $user->display_name
                    ])->send();
                if($user->save()) {
                    $this->flashSession->success('Reset password successfully');
                } else {
                    $this->flashSession->notice('System is busy, please try again later');
                }

                $this->response->redirect('/user/login/');
                return;
            }
        }
    }

    /**
     * Check token reset password
     *
     * @return bool|Users
     */
    private function checkToken()
    {
        $token = $this->request->get('token');
        if(strlen($token)) {
            /**
             * @var Users $user
             */
            $user = Users::findFirst([
                'conditions' => "is_active = 1 AND reset_password_token = ?0 AND reset_password_token <> ''",
                'bind' => [$token]
            ]);
            if($user) {
                if(strtotime($user->reset_password_token_at) + 2 * 86400 >= time()) {
                    return $user;
                } else {
                    $this->flashSession->error('Token has expired');
                    return false;
                }
            }
        }
        return false;
    }
}