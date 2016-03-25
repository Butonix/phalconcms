<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Auth\Controllers;

use Core\Social\Google;
use Core\FrontController;
use Core\Social\SocialHelper;

class GoogleController extends FrontController
{
    /**
     * Login callback
     */
    public function loginAction()
    {
        $google = Google::getInstance();
        if($google->isReady) {
            $this->_process($google);
        } else {
            $code = $this->request->get('code', 'string', '');
            if($code) {
                $google->checkRedirectCode($code);
                $status = $this->_process($google);
                if($status['success'] && $status['message'] == null) {
                    $this->response->redirect('/');
                } elseif($status['success'] && $status['message'] != null) {
                    $this->flashSession->success($status['message']);
                    $this->response->redirect('/user/login/');
                } elseif(!$status['success']) {
                    $this->flashSession->notice($status['message']);
                    $this->response->redirect('/user/login/');
                }
            } else {
                $this->response->redirect('/');
            }
        }
    }

    /**
     * Process login with Google
     *
     * @param Google $google
     * @return array
     */
    private function _process($google)
    {
        $userInfo = $google->getUserInfoToCreateAccount();
        return (new SocialHelper($userInfo, 'google'))->process();
    }
}