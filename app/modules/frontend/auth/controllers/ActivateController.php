<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Auth\Controllers;

use Core\Social\SocialHelper;
use Core\FrontController;

class ActivateController extends FrontController
{
   public function indexAction() {
       $token = $this->request->get('token', 'string', '');
       $status = SocialHelper::processActivateWithToken($token);
       if($status) {
           $this->flashSession->success('Active account successfully!');
           $this->response->redirect('/');
       } else {
           $this->flashSession->error('Active account failed!');
           $this->response->redirect('/user/login/');
       }
       return;
   }
}