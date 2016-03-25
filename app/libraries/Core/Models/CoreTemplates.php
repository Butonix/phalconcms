<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core\Models;

use Phalcon\Mvc\Model;

class CoreTemplates extends Model
{
    /**
     *
     * @var integer
     */
    public $template_id;

    /**
     *
     * @var string
     */
    public $base_name;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $location;

    /**
     *
     * @var string
     */
    public $uri;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $author;

    /**
     *
     * @var string
     */
    public $authorUri;

    /**
     *
     * @var string
     */
    public $tag;

    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var integer
     */
    public $published;

    /**
     * Initialize method for model
     */
    public function initialize()
    {

    }

    /**
     * Set default template
     *
     * @param $base_name
     * @param $location
     * @return bool
     */
    public function setDefaultTemplate($base_name, $location)
    {
        if(!($location == 'frontend' || $location == 'backend')) {
            $this->getDI()->get('flashSession')->error('location_template_must_be_backend_or_frontend');
            return false;
        }

        /**
         * @var CoreTemplates $defaultTemplate
         */
        $defaultTemplate = CoreTemplates::findFirst([
            'conditions' => "location = ?0 AND base_name = ?1",
            'bind' => [$location, $base_name]
        ]);

        if($defaultTemplate) {
            $phql = "UPDATE Core\Models\CoreTemplates SET published = 0 WHERE location = '{$location}'";
            if($this->getDI()->get('modelsManager')->createQuery($phql)->execute()) {
                $defaultTemplate->published = 1;
                if($defaultTemplate->save()) {
                    file_put_contents(APP_PATH . "/" . $location . "/index.volt", '{% extends "../../../../templates/' . $location . "/" . $base_name . '/index.volt" %}');
                    return true;
                }
            }
        }
        return false;
    }
}