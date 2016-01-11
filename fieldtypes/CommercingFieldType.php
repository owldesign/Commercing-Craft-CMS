<?php
/**
 * Commercing plugin for Craft CMS
 *
 * Commercing FieldType
 *
 * --snip--
 * Whenever someone creates a new field in Craft, they must specify what type of field it is. The system comes with
 * a handful of field types baked in, and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 * --snip--
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://owl-design.net
 * @package   Commercing
 * @since     0.0.1
 */

namespace Craft;

class CommercingFieldType extends BaseFieldType
{
    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Commercing');
    }

    /**
     * Returns the content attribute config.
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    /**
     * Returns the field's input HTML.
     *
     * @param string $name
     * @param mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        if (!$value)
            $value = new Commercing_FieldModel();

        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);

        // Include our Javascript & CSS
        craft()->templates->includeCssResource('commercing/css/field.css');
        craft()->templates->includeJsResource('commercing/js/field.js');

/* -- Variables to pass down to our field.js */

        $jsonVars = array(
            'id' => $id,
            'name' => $name,
            'namespace' => $namespacedId,
            'prefix' => craft()->templates->namespaceInputId(""),
            );

        $jsonVars = json_encode($jsonVars);
        craft()->templates->includeJs("$('#{$namespacedId}').CommercingFieldType(" . $jsonVars . ");");

/* -- Variables to pass down to our rendered template */

        $variables = array(
            'id' => $id,
            'name' => $name,
            'namespaceId' => $namespacedId,
            'values' => $value
            );

        return craft()->templates->render('commercing/field', $variables);
    }

    /**
     * Returns the input value as it should be saved to the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
    }

    /**
     * Prepares the field's value for use.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValue($value)
    {
    }
}