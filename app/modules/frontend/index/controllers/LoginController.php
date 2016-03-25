<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Index\Controllers;

use Phalcon\Validation;
use Core\Models\Users;
use Core\Social\Facebook;
use Core\Social\Google;
use Core\FrontController;
use Phalcon\Validation\Validator\Email;

class LoginController extends FrontController
{
    /**
     * User login
     */
    public function indexAction()
    {
        // User has login yet
        if($this->_user) {
            $this->session->remove('auth');
            unset($_SESSION);
        }

        $this->_addSocialLogin();

        // Regular login
        if($this->request->isPost()) {
            $validation = new Validation();
            $validation->add('email', new Email());

            $messages = $validation->validate($this->request->getPost());
            if(count($messages)) {
                foreach($messages as $message) {
                    $this->flashSession->error($message);
                }
                $this->response->redirect('/user/login/');
                return;
            }

            $email = strtolower($this->request->getPost('email', 'email'));
            $password = $this->request->getPost('password', 'string');

            $status = Users::login($email, $password);
            if($status === true) {
                $user = Users::getCurrentUser();
                $this->flashSession->success('Hi, ' . $user['full_name']);
                $this->response->redirect('/');
            } elseif($status === false) {
                $this->flashSession->error('User or password not match');
                $this->response->redirect('/user/login/');
            } else {
                $this->flashSession->error('Your account is not active yet');
                $this->response->redirect('/user/login/');
            }
        }
    }

    /**
     * Add social login
     */
    private function _addSocialLogin(){
        $isSocialLogin = false;
        if($this->config->social->facebook->appID){
            $fb = Facebook::getInstance();
            $helper = $fb->getRedirectLoginHelper();
            $permissions = $this->config->social->facebook->permissions->toArray();
            $this->view->setVar('facebookLoginUrl', $helper->getLoginUrl(BASE_URI . '/auth/facebook/login-callback/', $permissions));
            $isSocialLogin = true;
        }

        if($this->config->social->google->clientID){
            $google = Google::getInstance();
            $this->view->setVar('googleLoginUrl', $google->getAuthUrl());
            $isSocialLogin = true;
        }

        $this->view->setVar('isSocialLogin', $isSocialLogin);
    }
}