<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Menu\Controllers;

use Core\Router;
use Core\BackendController;
use Core\Models\CoreModules;

class RouterController extends BackendController
{
    /**
     * Build Link
     *
     * @param string $moduleName
     * @param string $type
     * @param int $currentPage
     * @param string $title
     */
    public function buildAction($moduleName, $type, $currentPage = 1, $title = null)
    {
        $title = strtolower($title);
        $routerClass = "Router" . ucfirst($moduleName) . 'Helper';
        $routerFile = APP_PATH . "/frontend/{$moduleName}/" . $routerClass . '.php';

        if(file_exists($routerFile)) {
            require_once($routerFile);
			
            $routerHelper = new $routerClass();
            if(method_exists($routerHelper, 'getMenu')) {
                $this->view->setVar('_page', $routerHelper->getMenu($type, $currentPage, $title));
                $this->view->setVar('request_title', urlencode($title) . '/');
                $this->view->setVar('request_link', BASE_URI . "/admin/menu/router/build/{$moduleName}/{$type}/");
                $this->view->setVar('request_title', $title);
            }
        } else {
            $this->view->setVar('error', __('m_menu_file_router_helper_file_not_found', [1 => $routerFile]));
        }
    }

    /**
     * Get menu (Ajax request)
     */
    public function menuAction()
    {
        if($this->request->isAjax()) {
            $this->view->setVar('menuModule', $this->getMenuModules());
        }
    }

    /**
     * Get menu module
     *
     * @param $module
     * @return mixed|bool Return Array if success, false if module doesn't support
     */
    public final function getMenuModule($module)
    {
        $menu['name'] = __($module->name);
        $menu['items'] = [];

        $routerClassName = 'Router' . ucfirst($module->base_name) . 'Helper';
        $file = APP_PATH . '/modules/frontend/' . $module->base_name . '/' . $routerClassName . '.php';

        if(file_exists($file)) {
            require_once $file;
            if(class_exists($routerClassName)) {
                /**
                 * @var Router $class
                 */
                $class = new $routerClassName();
                return $class->getMenuModule($module);
            }
        }
		
        return false;
    }

    /**
     * Get all menu for module frontend
     *
     * @return array
     */
    public final function getMenuModules()
    {
        /**
         * @var CoreModules[] $frontModules
         */
        $frontModules = CoreModules::find("location = 'frontend'");
        $moduleMenuType = [];
        if(count($frontModules)) {
            foreach($frontModules as $module) {
                $menu = $this->getMenuModule($module);;
                if($menu) {
                    $moduleMenuType[] = $menu;
                }
            }
        }
        return $moduleMenuType;
    }
}