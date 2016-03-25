<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Forms\Element;

use Phalcon\Forms\Element;
use Phalcon\Forms\ElementInterface;
use Phalcon\Tag;

class Date extends Element implements ElementInterface
{
    /**
     * Renders the element widget
     *
     * @param array $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        if(isset($attributes['class'])) {
            $attributes['class'] .= ' date-picker';
            if(strpos($attributes['class'], 'form-control') !== false) {
                $attributes['class'] .= ' form-control';
            }
        } else {
            $attributes['class'] = 'form-control date-picker';
        }
        $attributes['data-date-format'] = 'dd-mm-yyyy';
        return Tag::textField($this->prepareAttributes($attributes));
    }
}