<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Di;
use Core\Cache\Cache;
use Core\Models\CoreWidgetValues;

class Sidebar
{
    const CACHE_KEY = 'SIDEBAR_KEY_';

    /**
     * Get sidebar
     *
     * @param string $sidebar_base_name
     * @return string
     */
    public static function getSidebar($sidebar_base_name)
    {
        /**
         * @var Cache $cache
         */
        $cache = Di::getDefault()->get('cache');
        $sidebarKey = self::CACHE_KEY . $sidebar_base_name;

        $html = '';
        $defaultTemplate = Di::getDefault()->get('config')->frontendTemplate->defaultTemplate;

        $widgets = $cache->get($sidebarKey);
        if($widgets === null) {
            $widgets = CoreWidgetValues::find([
                'conditions' => 'sidebar_base_name = ?1 AND theme_name = ?2',
                'bind' => [1 => $sidebar_base_name, 2 => $defaultTemplate],
                'order' => 'ordering ASC'
            ])->toArray();
            $cache->save($sidebarKey, $widgets);
        }

        if(count($widgets) > 0) {
            // Get widget html
            foreach($widgets as $widget) {
                $class_name = explode('_', $widget['class_name'])[0];
                if(!class_exists($widget['class_name'])) {
                    $widget_file = APP_PATH . '/widgets/frontend/' . $class_name . '/' . $class_name . '.php';
                    if(file_exists($widget_file)) {
                        require_once $widget_file;
                    }
                }
                if(class_exists($widget['class_name'])) {
                    /**
                     * @var \Core\Widget $ob
                     */
                    $ob = new $widget['class_name']($widget['widget_value_id']);
                    if(method_exists($ob, 'widget')) {
                        $html .= $ob->getWidget();
                    }
                }
            }

        }
        return $html;
    }
}