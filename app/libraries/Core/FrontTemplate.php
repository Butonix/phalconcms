<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Events\Event;
use Phalcon\Mvc\View;

class FrontTemplate
{
    /**
     * @var string Module name need overwrite template;
     */
    protected $moduleBaseName = '';

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
     * After render
     *
     * @param Event $event
     * @param View $view
     */
    public function afterRender(Event $event, View $view)
    {
        // Do something
    }

    /**
     * Before render view
     *
     * @param Event $event
     * @param View $view
     */
    public function beforeRender(Event $event, View $view)
    {
        $defaultTemplate = $view->getDI()->get('config')->frontendTemplate->defaultTemplate;
        $viewDir = APP_PATH . '/templates/frontend/' . $defaultTemplate . '/modules/' . $this->moduleBaseName . '/';
        $pathView = $viewDir . $view->getControllerName() . '/' . $view->getActionName();
        $view->setVar('_templateDir', APP_PATH . '/templates/frontend/' . $defaultTemplate);
        if(realpath($pathView . '.volt')) {
            $view->setVar('_flashSession', '../../flashSession');
            $view->setVar('_header', '../../header');
            $view->setVar('_footer', '../../footer');
            $view->setVar('_sidebar', '../../sidebar');
            $view->setViewsDir($viewDir);
        } else {
            if(isset($view->_defaultTemplate)) {
                $view->setVar('_flashSession', '../../../../templates/frontend/' . $view->_defaultTemplate . '/flashSession');
                $view->setVar('_header', '../../../../templates/frontend/' . $view->_defaultTemplate . '/header');
                $view->setVar('_footer', '../../../../templates/frontend/' . $view->_defaultTemplate . '/footer');
                $view->setVar('_sidebar', '../../../../templates/frontend/' . $view->_defaultTemplate . '/sidebar');
            }
        }
    }
}
