<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models\Behavior;

trait SEOTable
{
    /**
     * @var string
     */
    public $meta_desc = '';

    /**
     * @var string
     */
    public $meta_keywords = '';

    /**
     * @var array
     */
    public $metadata = '';
}