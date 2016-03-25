<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class CoreWidgetValues extends Model
{
    /**
     *
     * @var integer
     */
    public $widget_value_id;

    /**
     *
     * @var string
     */
    public $sidebar_base_name;

    /**
     *
     * @var string
     */
    public $theme_name;

    /**
     *
     * @var string
     */
    public $class_name;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $options;

    /**
     *
     * @var integer
     */
    public $ordering;

    /**
     *
     * @var integer
     */
    public $published;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}