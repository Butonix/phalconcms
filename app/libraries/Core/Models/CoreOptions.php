<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;
use Core\Cache\Cache;

class CoreOptions extends Model
{
    /**
     * Cache options key
     */
    const CACHE_MODEL_CORE_OPTIONS = 'CACHE_MODEL_CORE_OPTIONS';

    /**
     * @var int
     */
    public $option_id;

    /**
     * @var string
     */
    public $option_scope;

    /**
     * @var string
     */
    public $option_name;

    /**
     * @var string
     */
    public $option_value;

    /**
     * If value equal 1 then option autoload to CACHE
     *
     * @var int Value in [0,1]
     * @return array
     */
    public $autoload;

    public static function initOrUpdateCacheOptions($reloadCache = false)
    {
        $cache = Cache::getInstance(APPLICATION);
        // Load cache options
        $optionsCache = $cache->get(self::CACHE_MODEL_CORE_OPTIONS);
        // If reload cache Or current cache is null
        if($reloadCache || $optionsCache === null) {
            $options = self::find([
                'columns' => ['option_scope', 'option_name', 'option_value'],
                'conditions' => 'autoload = 1'
            ]);
            $optionsCache = [];
            foreach($options as $option) {
                $optionsCache[$option->option_name . '_' . $option->option_scope] = $option->option_value;
            }
            $cache->save(self::CACHE_MODEL_CORE_OPTIONS, $optionsCache);
        }
        return $optionsCache;
    }

    public static function getOptions($name, $scope = '', $default = null)
    {
        $cache = Cache::getInstance(APPLICATION);
        $optionsCache = $cache->get(self::CACHE_MODEL_CORE_OPTIONS);
        if($optionsCache === null) {
            $optionsCache = self::initOrUpdateCacheOptions(true);
        }
        if(isset($optionsCache[$name . '_' . $scope])) {
            return $optionsCache[$name . '_' . $scope];
        }
        return $default;
    }

    /**
     * Execute before create
     */
    public function beforeCreate()
    {
        if($this->autoload) {
            self::initOrUpdateCacheOptions(true);
        }
    }

    /**
     * Execute before update
     */
    public function beforeUpdate()
    {
        if($this->autoload) {
            self::initOrUpdateCacheOptions(true);
        }
    }
}