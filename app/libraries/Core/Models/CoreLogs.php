<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class CoreLogs extends Model
{
    /**
     * @var int
     */
    public $log_id;

    /**
     * @var string
     */
    public $log_module;

    /**
     * @var string
     */
    public $log_content;

    /**
     * @var string error|success|notice
     */
    public $status;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}