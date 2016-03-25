<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Admin\Controllers;

use Core\BackendController;

class IndexController extends BackendController
{
    /**
     * @var bool
     */
    public $_autoTranslateToolbar = false;

    /**
     * Default view when user logged in
     */
    public function indexAction()
    {
        // Add information for dashboard
        $this->_toolbar->addHeaderPrimary('m_admin_admin');
        $this->_toolbar->addHeaderSecond($this->config->website->systemName);
        $this->_toolbar->addBreadcrumb('m_admin_admin');
    }
}