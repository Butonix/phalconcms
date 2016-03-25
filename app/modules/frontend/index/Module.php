<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Index;

use Core\FrontModule;

class Module extends FrontModule
{
    /**
     * Define module name
     *
     * @var string
     */
    protected $module_name = 'index';

    /**
     * Module Constructor
     */
    public function __construct()
    {
        parent::__construct($this->module_name);
    }
}
