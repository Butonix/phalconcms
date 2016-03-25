<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Di;
use Phalcon\Events\Event;
use Phalcon\Mvc\View;
use Core\Forms\FormFilter;
use Core\Utilities\ToolbarHelper;

class BackendTemplate
{
    /**
     * @var string Module name need overwrite template
     */
    protected $moduleBaseName = "";

    /**
     * Instance construct
     *
     * @param string $moduleBaseName
     */
    public function __construct($moduleBaseName)
    {
        $this->moduleBaseName = $moduleBaseName;
    }

    /**
     * After render view
     *
     * @param Event $event
     * @param View $view
     */
    public function afterRender($event, $view)
    {
        // Do something
    }

    /**
     * Before render
     *
     * @param Event $event
     * @param View $view
     * @return View
     */
    public function beforeRender($event, $view)
    {
        $view->setVar('_limit', $view->getDI()->get('config')->pagination->limit);
        if(isset($view->_pageLayout) && isset($view->_filter)) {
            $filter = array_column($view->_pageLayout, 'filter');
            if(!empty($filter)) {
                $filterForm = new FormFilter($filter, $view->_filter);
                $view->setVar('_filterColumn', $filterForm->getForm());
            }
        }
        $view->setVar('_toolbarHelpers', ToolbarHelper::getInstance($this->moduleBaseName, $view->getControllerName()));
    }

    /**
     * Before render view
     *
     * @param Event $event
     * @param View $view
     * @return View
     */
    public function beforeRenderView($event, $view)
    {

    }
}