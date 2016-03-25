<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\User\Controllers;

use Core\BackendController;

class ForgotPasswordController extends BackendController
{
    public function indexAction()
    {
        // User has login yet
        if($this->_user) {
            $this->session->destroy();
        }

        if($this->request->isPost()) {
            echo '<pre>';
            var_dump('x');
            echo '</pre>';
            die();
        }
    }
}