<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use  Phalcon\Mvc\Model;

class CountryStates extends Model
{
    /**
     *
     * @var integer
     */
    public $country_state_id;

    /**
     *
     * @var integer
     */
    public $country_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $short_name;

    /**
     *
     * @var integer
     */
    public $ordering;

    /**
     *
     * @var string
     */
    public $alias;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

        $this->belongsTo('country_id', 'Countries', 'country_id', ['alias' => 'Countries']);
    }
}