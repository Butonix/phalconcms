<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Social;

use Phalcon\Di;
use Google_Client;
use Core\Factory;
use Google_Service_Plus;

require_once APP_PATH . '/libraries/Google/autoload.php';

class Google
{
    const SOCIAL_GOOGLE_ACCESS_TOKEN = '_SOCIAL_GOOGLE_ACCESS_TOKEN';

    /**
     * @var array Array construct $config = ['clientID' => '', 'clientSecret' => '', 'scope' => '']
     */
    private $config;

    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * @var
     */
    public $isReady = false;

    /**
     * @var Google
     */
    protected static $instance;

    /**
     * Instance Google
     *
     * @param array $config
     * @return Google
     */
    public static function getInstance(array $config = [])
    {
        if(!is_object(self::$instance)) {
            self::$instance = new Google($config);
        }
        return self::$instance;
    }

    /**
     * Get Client
     *
     * @return Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Instantiates a new Google
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if(!count($config)) {
            $sysConfig = Factory::getConfig();
            $config = [
                'clientID' => $sysConfig->social->google->clientID,
                'clientSecret' => $sysConfig->social->google->clientSecret,
                'scope' => $sysConfig->social->google->scope->toArray()
            ];
        }
        $this->config = $config;
        $this->_initGoogleClient();
    }

    /**
     * Init Google Client
     */
    private function _initGoogleClient()
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->config['clientID']);
        $this->client->setClientSecret($this->config['clientSecret']);
        $this->client->setScopes($this->config['scope']);
        $this->client->setRedirectUri(BASE_URI . '/auth/google/login-callback/');
        // $session = Di::getDefault()->get('session');
        // $token = $session->get(self::SOCIAL_GOOGLE_ACCESS_TOKEN);
        // if($token && $this->client->verifyIdToken($token)) {
          // $this->client->setAccessToken($token);
          // $this->isReady = true;
        // }
    }

    /**
     * Get url oauth
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Check redirect code from url
     *
     * @param $code
     * @return bool
     */
    public function checkRedirectCode($code)
    {
        if($code) {
            $this->client->authenticate($code);
            $session = Di::getDefault()->get('session');
            $token = $this->client->getAccessToken();
            $session->set(self::SOCIAL_GOOGLE_ACCESS_TOKEN, $token);
            $this->client->setAccessToken($token);
            return true;
        }
        return false;
    }

    /**
     * Set the OAuth 2.0 access token using the string that resulted from calling createAuthUrl()
     *
     * @param string $token String JSON
     */
    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    /**
     * Get info
     *
     * @return array|bool Return Array if success or false if error
     */
    public function payload()
    {
        try {
            $info = $this->client->verifyIdToken()->getAttributes();
            if(is_array($info)) {
                $info = $info['payload'];
            } else {
                $info = false;
            }
        } catch(\Exception $e) {
            return false;
        }
        return $info;
    }

    public function getUserInfoToCreateAccount()
    {
        $me = $this->getMe();
        $payload = $this->payload();
        if(isset($payload['email'])){
            return [
                'email' => $payload['email'],
                'first_name' => $me->getName()->givenName,
                'last_name' => $me->getName()->familyName,
                'display_name' => $me->getDisplayName(),
            ];
        } else {
            return false;
        }
    }

    /**
     * @return \Google_Service_Plus_Person
     */
    public function getMe()
    {
        $plus = new Google_Service_Plus($this->client);
        return $plus->people->get('me');
    }
}