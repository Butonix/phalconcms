<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Frontend\Auth;

use Core\FrontModule;

class Module extends FrontModule
{
    /**
     * Define module name
     *
     * @var string
     */
    protected $module_name = 'auth';

    /**
     * Module Constructor
     */
    public function __construct()
    {
        parent::__construct($this->module_name);
    }
}