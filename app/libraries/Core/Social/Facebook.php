<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Social;

use Facebook\Facebook;
use Core\Factory;

require_once APP_PATH . '/libraries/Facebook/autoload.php';

class Facebook extends Facebook
{
    /**
     * @var Facebook
     */
    protected static $instance;

    /**
     * Instance Facebook
     *
     * @param array $config
     * @return Facebook
     */
    public static function getInstance(array $config = [])
    {
        if(!is_object(self::$instance)) {
            self::$instance = new Facebook($config);
        }
        return self::$instance;
    }

    /**
     * Instantiates a new Facebook super-class object
     *
     * @param array $config
     *
     * @throws \Facebook\Exceptions\FacebookSDKException;
     */
    public function __construct(array $config = [])
    {
        if(!count($config)) {
            $sysConfig = Factory::getConfig();
            $config = [
                'app_id' => $sysConfig->social->facebook->appID,
                'app_secret' => $sysConfig->social->facebook->appSecret,
                'permissions' => $sysConfig->social->facebook->permissions,
                'default_graph_version' => $sysConfig->social->facebook->defaultGraphVersion ? $sysConfig->social->facebook->defaultGraphVersion : 'v2.2',
            ];
        }
        parent::__construct($config);
    }
}