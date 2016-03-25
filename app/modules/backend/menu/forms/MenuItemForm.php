<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Menu\Forms;

use Core\Forms\Form;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class MenuItemForm extends Form
{
    /**
     * @var string
     */
    public $_formName = 'm_menu_form_menu_item';

    /**
     * Init form
     *
     * @param \Core\Models\MenuItems $data
     */
    public function initialize($data = null)
    {
        $name = new Text('name', ['required' => 'required']);
        $name->addValidator(new PresenceOf());
        $this->add($name);

        $class = new Text('class');
        $this->add($class);

        $icon = new Text('icon');
        $this->add($icon);

        $link = new Text('link');
        $this->add($link);

        $thumbnail = new File('thumbnail');
        $this->add($thumbnail);

        $published = new Select('published', [
            '1' => __('yes'),
            '0' => __('no'),
        ], [
            'value' => $data->published = 0 ? 0 : 1
        ]);
        $this->add($published);

        $require_login = new Select('require_login', [
            '0' => __('display_always'),
            '-1' => __('hidden_when_user_login'),
            '1' => __('display_when_user_login')
        ]);
        $this->add($require_login);
    }
}