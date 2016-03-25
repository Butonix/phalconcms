<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Backend\Menu\Forms;

use Phalcon\Forms\Element\TextArea;
use Core\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class MenuTypeForm extends Form
{
    /**
     * @var string
     */
    public $_formName = 'm_menu_form_menu_type';

    /**
     * @var bool
     */
    public $_autoGenerateTranslateHelpLabel = false;

    /**
     * Init form
     *
     * @param \Core\Models\MenuItems $data
     */
    public function initialize($data = null)
    {
        $name = new Text("name");
        $name->addValidator(new PresenceOf());
        $this->add($name);

        $description = new TextArea("description");
        $this->add($description);

        $published = new Select("published", [
            "1" => __("yes"),
            "0" => __("no")
        ]);
        $this->add($published);
    }
}