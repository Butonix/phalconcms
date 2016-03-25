<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;

class CoreSidebars extends Model
{
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
    public $sidebar_name;

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
     *
     * @var string
     */
    public $location;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}