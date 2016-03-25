<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

try {
    error_reporting(E_ALL);
    ini_set('xdebug.var_display_max_depth', -1);
    ini_set('xdebug.var_display_max_children', -1);
    ini_set('xdebug.var_display_max_data', -1);

    (new Phalcon\Debug())->listen();

    /**
     * Define useful constants
     */
    define('ROOT_PATH', dirname(__DIR__));
    define('APP_PATH', ROOT_PATH . '/app');
    require_once APP_PATH . '/config/define.php';

    if(is_dir(APP_PATH . '/modules/install/') && file_exists(ROOT_PATH . '/public/install.php')) {
        if($_SERVER['SERVER_PORT'] != '443') {
            $baseUri = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME']);
        } else {
            $baseUri = 'https://' . $_SERVER['HTTP_HOST'] . str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME']);
        }
        header("Location:  {$baseUri}/install.php?step=1");
        die();
    }
    /**
     * Require Core
     */
    require_once APP_PATH . '/libraries/Core/Factory.php';
    require_once APP_PATH . '/libraries/Core/ApplicationInit.php';
    require_once APP_PATH . '/libraries/Core/Application.php';

    /**
     * Create Application
     */
    $application = new \Core\Application();

    /**
     * Run Application
     */
    echo $application->run()->getContent();
} catch(Exception $e) {
    echo $e->getMessage();
}