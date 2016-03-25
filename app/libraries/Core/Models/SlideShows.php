<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class SlideShows extends Model
{
    /**
     *
     * @var integer
     */
    public $slide_show_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $alias;

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
     * @var integer
     */
    public $published;

    public function beforeSave()
    {
        if($this->alias == '') {
            $this->alias = generateAlias($this->title);
        }
    }

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}