<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class CoreMedia extends Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $alt_text;

    /**
     *
     * @var string
     */
    public $caption;

    /**
     *
     * @var integer
     */
    public $size;

    /**
     *
     * @var integer
     */
    public $description;

    /**
     *
     * @var string
     */
    public $mime_type;

    /**
     *
     * @var string
     */
    public $src;

    /**
     *
     * @var string
     */
    public $information;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}