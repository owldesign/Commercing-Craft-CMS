<?php
/**
 * Commercing plugin for Craft CMS
 *
 * Commercing Variable
 *
 * --snip--
 * Craft allows plugins to provide their own template variables, accessible from the {{ craft }} global variable
 * (e.g. {{ craft.pluginName }}).
 *
 * https://craftcms.com/docs/plugins/variables
 * --snip--
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://owl-design.net
 * @package   Commercing
 * @since     0.0.1
 */

namespace Craft;

class CommercingVariable
{
    /**
     * Whatever you want to output to a Twig tempate can go into a Variable method. You can have as many variable
     * functions as you want.  From any Twig template, call it like this:
     *
     *     {{ craft.commercing.exampleVariable }}
     *
     * Or, if your variable requires input from Twig:
     *
     *     {{ craft.commercing.exampleVariable(twigValue) }}
     */
    public function exampleVariable($optional = null)
    {
        return "And away we go to the Twig template...";
    }
}