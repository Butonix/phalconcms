<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Loader;
use Phalcon\Logger;
use Phalcon\Mvc\Router;
use Core\Plugins\Acl;
use Core\Cache\Cache;
use Core\Assets\Assets;
use Phalcon\Di\FactoryDefault;
use Core\Utilities\Crypt;
use Phalcon\Security;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Cache\Backend\File as CacheFile;
use Phalcon\Cache\Frontend\Output as FrontendOutput;
use Phalcon\Mvc\Model\MetaData\Apc as MetaDataApc;
use Phalcon\Mvc\Model\MetaData\Files as MetadataFiles;
use Phalcon\Session\Adapter\Files as Session;

trait ApplicationInit
{
    /**
     * Init loader
     *
     * @param \Phalcon\DiInterface $di
     * @return Loader
     */
    public function _initLoader($di)
    {
        $loader = new Loader();

        // Register plugins
        /*$loader->registerDirs([
            APP_PATH . '/plugins/',
        ])->register();*/

        // Register some namespaces
        $loader->registerNamespaces(
            [
                'Core' => APP_PATH . '/libraries/Core/'
            ],
            true
        );
        $loader->register();
        $di->set('loader', $loader);
        return $loader;
    }

    /**
     * Init Services
     *
     * @param mixed $config
     * @param \Phalcon\DiInterface $di
     */
    public function _initServices($di, $config)
    {
        /**
         * The URL component is used to generate all kind of urls in the application
         */
        $di->set('url', function() use ($config) {
            $url = new UrlResolver();
            $url->setBaseUri($config->website->baseUri);
            return $url;
        }, true);

        /**
         * Start the session the first time some component request the session service
         */
        $di->set('session', function() use ($config) {
            $session = new Session([
                'uniqueId' => $config->auth->salt
            ]);
            $session->start();
            return $session;
        }, true);

        /**
         * Set view cache
         */
        $di->set('viewCache', function() use ($config) {
            // Cache data for one day by default
            $frontCache = new FrontendOutput([
                'lifetime' => $config->viewCache->lifetime
            ]);
			
            // File backend settings
			$cacheDir = ROOT_PATH . $config->viewCache->dir;
			if(!is_dir($cacheDir)) {
				mkdir($cacheDir, 0755, true);
			}
            $cache = new CacheFile($frontCache, [
                'cacheDir' => $cacheDir
            ]);
            return $cache;
        });

        if($config->modelMetadataCache->status) {
            /**
             * Set models metadata
             */
            $di->set('modelsMetadata', function() use ($config) {
                if($config->modelMetadataCache->type == 'apc') {
                    return new MetaDataApc([
                        'lifetime' => $config->modelMetadataCache->lifetime,
                        'prefix' => $config->modelMetadataCache->prefix,
                    ]);
                } else {
					$metaDataDir = ROOT_PATH . '/var/cache/metadata/';
					if(!is_dir($metaDataDir)) {
						mkdir($metaDataDir, 0755, true);
					}
					
                    return new MetadataFiles([
                        'metaDataDir' => $metaDataDir,
                        'lifetime' => $config->modelMetadataCache->lifetime
                    ]);
                }
            });
        }

        /**
         * Crypt service
         */
        $di->set('crypt', function() use ($config) {
            $crypt = Crypt::getInstance();
            $crypt->setKey($config->crypt->key);
            return $crypt;
        });

        /**
         * Set security
         */
        $di->set('security', function() {
            $security = new Security();
            $security->setWorkFactor(8);
            return $security;
        });

        /**
         * Set up database connection
         */
        $di->set('db', function() use ($config) {
            $adapter = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;

            if($config->database->adapter == 'Mysql') {
                $db = new $adapter($config->database->toArray());
            } else {
                $db = new $adapter(array(
                    'host' => $config->database->host,
                    'username' => $config->database->username,
                    'password' => $config->database->password,
                    'dbname' => $config->database->dbname
                ));
            }

            if($config->database->log) {
                $eventsManager = new EventsManager();
				
				$logDir = ROOT_PATH . '/var/logs/db';
                if(!is_dir($logDir)){
                    mkdir($logDir, 0755, true);
                }
				
                $logger = new FileLogger($logDir . '/' . date('Y-m-d') . '.log');
				
                // Listen all the database events
                $eventsManager->attach('db', function($event, $db) use ($logger) {
                    if($event->getType() == 'beforeQuery') {
                        $logger->log($db->getSQLStatement(), Logger::INFO);
                    }
                });
				
                // Assign the eventsManager to the db adapter instance
                $db->setEventsManager($eventsManager);
            }
            return $db;
        });

        /**
         * Set a models manager
         */
        $di->set('modelsManager', new ModelsManager());

        /**
         * Set up model cache for Phalcon model
         */
        $di->set('modelsCache', function() {
            return Cache::getInstance('_MODEL');
        });

        /**
         * Set up asset add css, js
         */
        $di->set('assets', new Assets());

        /**
         * Loading routes from the routes.php file
         */
        $di->set('router', function() {
            return require APP_PATH . '/config/router.php';
        });

        $di->set('acl', Acl::getInstance());

        /**
         * Set up the flash service (custom with bootstrap)
         */
        $di->set('flashSession', function() {
            $flashSession = new FlashSession([
                'warning' => 'alert alert-warning',
                'notice' => 'alert alert-info',
                'success' => 'alert alert-success',
                'error' => 'alert alert-danger'
            ]);
            return $flashSession;
        });

        /**
         * Set up cache
         */
        $di->set('cache', Cache::getInstance('_GLOBAL'));
    }
}