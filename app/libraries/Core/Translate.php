<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Di;
use Core\Cache\Cache;
use Phalcon\Translate\Adapter\NativeArray;

class Translate
{
    const Core_Translate = 'Core_Translate';

    /**
     * @var Translate
     */
    public static $instance;

    /**
     * Language code. Eg en_US, fr-FR
     *
     * @var string
     */
    public $language;

    /**
     * Array code translation
     *
     * @var array
     */
    public $translation = [];

    /**
     * Get instance Translate
     *
     * @return Translate
     */
    public static function getInstance()
    {
        if(!is_object(self::$instance)) {
            self::$instance = new Translate();
        }
        return self::$instance;
    }

    /**
     * Instance construct
     */
    public function __construct()
    {
        $this->language = Di::getDefault()->get('config')->website->language;
        global $APP_LOCATION;
        if($APP_LOCATION) {
            $cache = Cache::getInstance();
            $translation = $cache->get('Core_Translate' . $APP_LOCATION);

            if($translation === null) {
                $modules = get_child_folder(APP_PATH . "/modules/{$APP_LOCATION}/");
                $this->addLang(APP_PATH . '/languages/en_US/en_US.php');
                $this->addLang(APP_PATH . '/languages/' . $this->language . '/' . $this->language . '.php');
                $this->addModuleLang($modules, $APP_LOCATION);
                $cache->save('Core_Translate', $this->translation);
            } else {
                $this->setTranslate($translation);
            }
        }
    }

    /**
     * Method add a language file in the translate
     *
     * @param string $filePath
     * @return bool
     */
    public function addLang($filePath = '')
    {
        if(file_exists($filePath)) {
            $contentLang = require_once($filePath);
            if($contentLang === true) {
                return true;
            }
            if(is_array($contentLang)) {
                $this->translation = array_merge($this->translation, $contentLang);
            } else {
                if(DEBUG) {
                    Di::getDefault()->get('flashSession')->error('Error file translation ' . $filePath);
                }
                return false;
            }
            return true;
        }
        if(DEBUG) {
            Di::getDefault()->get('flashSession')->warning('File translation not found ' . $filePath);
        }
        return false;
    }

    /**
     * Add a module language file in the translate
     *
     * @param string|mixed $moduleName
     * @param string $location
     */
    public function addModuleLang($moduleName, $location = 'backend')
    {
        if(is_array($moduleName)) {
            foreach($moduleName as $module_base_name) {
                $basePath = APP_PATH . '/modules/' . $location . '/' . $module_base_name . '/languages';
                $this->addLang($basePath . '/en_US/en_US.php');
                if($this->language != 'en_US') {
                    $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
                }
            }
        } elseif(gettype($moduleName) == 'string') {
            $basePath = APP_PATH . '/modules/' . $location . '/' . $moduleName . '/languages';
            $this->addLang($basePath . '/en_US/en_US.php');
            if($this->language != 'en_US') {
                $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
            }
        }
    }

    /**
     * Add a template language file in the translate
     *
     * @param string|mixed $templateName
     * @param string $location
     */
    public function addTemplateLang($templateName, $location = 'backend')
    {
        if(is_array($templateName)) {
            foreach($templateName as $template_base_name) {
                $basePath = APP_PATH . '/templates/' . $location . '/' . $template_base_name . '/languages';
                $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
            }
        } elseif(gettype($templateName) == 'string') {
            $basePath = APP_PATH . '/templates/' . $location . '/' . $templateName . '/languages';
            $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
        }
    }

    /**
     * Add a widget language file in the translate
     *
     * @param string|array $widgetName
     * @param string $location
     */
    public function addWidgetLang($widgetName, $location = 'backend')
    {
        if(is_array($widgetName)) {
            foreach($widgetName as $widget_base_name) {
                $basePath = APP_PATH . '/widgets/' . $location . '/' . $widget_base_name . '/languages';
                $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
            }
        } elseif(gettype($widgetName) == 'string') {
            $basePath = APP_PATH . '/widgets/' . $location . '/' . $widgetName . '/languages';
            $this->addLang($basePath . '/' . $this->language . '/' . $this->language . '.php');
        }
    }

    /**
     * Get Translate
     *
     * @return NativeArray
     */
    public function getTranslate()
    {
        $this->addLang(APP_PATH . '/languages/override/' . $this->language . '.php');
        return new NativeArray([
            'content' => $this->translation
        ]);
    }

    /**
     * Set Translate
     *
     * @param array $translation
     */
    public function setTranslate($translation)
    {
        $this->translation = $translation;
    }
}