<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Core\Model;

class UserRoleMapping extends Model
{
    /**
     *
     * @var integer
     */
    public $role_mapping_id;

    /**
     *
     * @var integer
     */
    public $role_id;

    /**
     *
     * @var integer
     */
    public $rule_id;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }
}