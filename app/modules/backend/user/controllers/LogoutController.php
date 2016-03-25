<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\User\Controllers;

use Core\BackendController;

class LogoutController extends BackendController
{
    /**
     * Logout Action
     */
    public function indexAction()
    {
        unset($_SESSION);
        $this->session->destroy();
        $this->response->redirect('/admin/user/login/');
        return;
    }
}