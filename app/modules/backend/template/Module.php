<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Template;

use Core\BackendModule;

class Module extends BackendModule
{
    /**
     * Define module name
     *
     * @var string
     */
    protected $module_name = 'template';

    /**
     * Module Constructor
     */
    public function __construct()
    {
        parent::__construct($this->module_name);
    }
}