<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use  Phalcon\Mvc\Model;

class CoreWidgets extends Model
{
    /**
     *
     * @var integer
     */
    public $widget_id;

    /**
     *
     * @var string
     */
    public $base_name;

    /**
     *
     * @var string
     */
    public $location;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $uri;

    /**
     *
     * @var string
     */
    public $author;

    /**
     *
     * @var string
     */
    public $authorUri;

    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var integer
     */
    public $published;

    /**
     *
     * @var integer
     */
    public $is_core;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}