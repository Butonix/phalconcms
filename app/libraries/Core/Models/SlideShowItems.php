<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class SlideShowItems extends Model
{
    /**
     *
     * @var integer
     */
    public $slide_show_item_id;

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
    public $image;

    /**
     *
     * @var string
     */
    public $link;

    /**
     *
     * @var string
     */
    public $target;

    /**
     *
     * @var integer
     */
    public $slide_show_id;

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