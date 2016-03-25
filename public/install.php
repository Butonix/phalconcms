<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

error_reporting(E_ALL);

try {
    /**
     * Define useful constants
     */
    define('ROOT_PATH', dirname(__DIR__));
    define('APP_PATH', ROOT_PATH . '/app');
    require_once APP_PATH . '/config/define.php';
    require_once(APP_PATH . '/libraries/Core/Utilities/Functions.php');

    if(!file_exists(APP_PATH . '/modules/install/')) {
        die();
    }

    /**
     * Read the configuration
     *
     * @var mixed $config
     */
    $config = new \Phalcon\Config\Adapter\Php(APP_PATH . '/config/config.php');

    if($config->website->baseUri == '') {
        if($_SERVER['SERVER_PORT'] != '443') {
            $config->website->baseUri = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(['/public/install.php', '/install.php'], '', $_SERVER['SCRIPT_NAME']);
        } else {
            $config->website->baseUri = 'https://' . $_SERVER['HTTP_HOST'] . str_replace(['/public/install.php', '/install.php'], '', $_SERVER['SCRIPT_NAME']);
        }
    }

    /**
     * Read auto-loader
     */
    include APP_PATH . '/modules/install/config/loader.php';

    /**
     * Read services
     */
    include APP_PATH . '/modules/install/config/services.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
} catch(\Exception $e) {
    echo $e->getMessage();
}