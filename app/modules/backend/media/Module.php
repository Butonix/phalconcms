<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Media;

use Core\BackendModule;

class Module extends BackendModule
{
    /**
     * Define module name
     *
     * @var string
     */
    protected $module_name = 'media';

    /**
     * Module Constructor
     */
    public function __construct()
    {
        parent::__construct($this->module_name);
    }
}