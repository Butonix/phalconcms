<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

$loader = new \Phalcon\Loader();

$loader->registerDirs([
	APP_PATH . '/modules/install/controllers/'
])->registerNamespaces([
    'Core' => APP_PATH . '/libraries/Core/',
])->register();