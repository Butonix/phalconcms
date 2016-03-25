<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

class PostCategory extends Categories
{
    public $module = 'content';

    public function getSource()
    {
        return 'categories';
    }
}