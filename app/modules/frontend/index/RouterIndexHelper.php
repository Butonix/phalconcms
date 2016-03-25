<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

use Core\Router;

class RouterIndexHelper extends Router
{
    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @var array
     */
    public $routerType = [
        [
            'type' => 'link',
            'menu_name' => 'Home Page',
            'menu_link' => '/'
        ],
        [
            'type' => 'link',
            'menu_name' => 'User Login',
            'menu_link' => '/user/login/'
        ],
        [
            'type' => 'link',
            'menu_name' => 'User Logout',
            'menu_link' => '/user/logout/'
        ],
    ];
}