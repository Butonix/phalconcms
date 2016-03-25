<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Index\Controllers;

use Core\FrontController;

class LogoutController extends FrontController
{
    /**
     * Logout Action
     */
    public function indexAction()
    {
        unset($_SESSION);
        $this->session->destroy();
        $this->response->redirect('/user/login/');
        $this->flashSession->success('You are logged out');
        return;
    }
}