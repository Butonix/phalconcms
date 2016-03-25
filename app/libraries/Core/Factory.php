<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Di;
use Phalcon\Config\Adapter\Php as ConfigPhp;

class Factory
{
    /**
     * Get default config
     *
     * @return ConfigPhp
     */
    public static function config()
    {
        return new ConfigPhp(APP_PATH . '/config/config.php');
    }

    /**
     * Get default config
     *
     * @return mixed|ConfigPhp
     */
    public static function getConfig()
    {
        return Di::getDefault()->get('config');
    }
}