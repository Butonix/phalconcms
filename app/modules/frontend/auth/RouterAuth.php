<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

use Phalcon\Mvc\Router\Group;

/**
 * Class RouterAuth
 */
class RouterAuth extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'auth',
            'namespace' => 'Frontend\Auth\Controllers'
        ]);

        $this->add('/auth/facebook/login-callback(/)?', [
            'controller' => 'facebook',
            'action' => 'login',
        ]);

        $this->add('/auth/google/login-callback(/)?', [
            'controller' => 'google',
            'action' => 'login',
        ]);

        $this->add('/auth/active(/)?', [
            'controller' => 'activate',
            'action' => 'index',
        ]);
    }
}