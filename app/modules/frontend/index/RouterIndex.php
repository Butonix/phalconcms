<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

use Phalcon\Mvc\Router\Group;

class RouterIndex extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'index',
            'namespace' => 'Frontend\Index\Controllers'
        ]);

        $this->setPrefix('/');

        $this->add('user/logout(/)?', [
            'controller' => 'logout',
            'action' => 'index',
        ]);

        $this->add('user/login(/)?', [
            'controller' => 'login',
            'action' => 'index',
        ]);

        $this->add('user/register(/)?', [
            'controller' => 'register',
            'action' => 'index',
        ]);

        $this->add('user/activate-account(/)?', [
            'controller' => 'register',
            'action' => 'activateAccount',
        ]);

        $this->add('user/forgot-password(/)?', [
            'controller' => 'forgot-password',
            'action' => 'index',
        ]);

        $this->add('user/reset-password(/)?', [
            'controller' => 'forgot-password',
            'action' => 'resetPassword',
        ]);
    }
}