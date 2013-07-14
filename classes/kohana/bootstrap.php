<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper class for Bootstrap.
 * 
 * This is designed as generic as possible. Inputs are safed against XSS and
 * other security breaches on Kohana layer.
 * 
 * @link http://twitter.github.com/bootstrap/
 * 
 * @package   Bootstrap
 * @category  Helpers
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_Bootstrap {

    const
            SUCCESS = 'success',
            INFO = 'info',
            WARNING = 'warning',
            ERROR = 'error',
            DANGER = 'danger';

    /**
     * Separators used in HTML attributes.
     * 
     * @var array 
     */
    protected static $attribute_separator = array(
        'class' => ' ',
        'style' => ';'
    );

    /**
     * Append an attributes without overriding existing attributes. class is
     * used by default.
     * 
     * @param array $attributes array of key-value pairs of attributes
     * @param type $value value to append
     * @param type $attribute name of the attribute
     */
    protected static function add_attribute(&$attributes, $attribute, $value) {

        $separator = Arr::get(Bootstrap::$attribute_separator, $attribute, ' ');

        $attributes[$attribute] = trim(Arr::get($attributes, $attribute, '') . $separator . $value, $separator);
    }

    /**
     * 
     * @param string $tag
     * @param string $body
     * @param array $variables
     * @param array $attributes
     * @return string
     */
    protected static function base($tag, $body, array $variables = NULL, array $attributes = NULL) {
        return "<$tag" . HTML::attributes($attributes) . '>' . __($body, $variables) . "</$tag>";
    }

    /**
     * Generates a Bootstrap alert.
     * 
     * @link 
     * 
     * @param string $message
     * @param array $variables
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function alert($message, array $variables = NULL, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "alert alert-$type");

        return Bootstrap::base('div', $message, $variables, $attributes);
    }

    /**
     * Generates a Bootstrap badge.
     * 
     * @link 
     * 
     * @param string $message
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function badge($message, array $variables = NULL, $type = 'info', $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "badge badge-$type");

        return Bootstrap::base('span', $message, $variables, $attributes);
    }

    /**
     * Generate a Bootstrap breadcrumb.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#breadcrumbs
     * 
     * @param array   $elements      elements
     * @param variant $actives       actives elements
     * @param array   $variables     substitution variables
     * @param string  $divider       divider between each element
     * @param array   $attributes    breadcrumb HTML attributes
     * @return string a beautiful breadcrumb
     */
    public static function breadcrumb(array $elements, $actives = NULL, array $variables = NULL, $divider = '/', array $attributes = NULL) {

        if ($actives === NULL) {
            $actives = array();
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        $divider = Bootstrap::base('span', $divider, $variables, array('class' => 'divider'));

        Bootstrap::add_attribute($attributes, 'class', 'breadcrumb');

        foreach ($elements as $key => &$element) {

            $li_attributes = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($li_attributes, 'class', 'active');
            }

            $element = Bootstrap::base('li', $element, $variables, $li_attributes);
        }

        return '<ul' . HTML::attributes($attributes) . '>' . implode($divider, $elements) . '</ul>';
    }

    /**
     * Generate a Bootstrap button.
     *
     * If $name and $value are set to NULL, it will make a div button.
     * 
     * If $name is set to NULL, it generates an anchor button using HTML::anchor.
     * 
     * Otherwise, it generates a Form::button.
     * 
     * @param string $text       text to display
     * @param string $name       name in a form
     * @param string $value      value in a form or href in an anchor
     * @param string $type       type for the button
     * @param array  $attributes attributes for the button
     * @return string            a well formed input, anchor or div button
     */
    public static function button($text, $name = NULL, $value = NULL, $type = '', array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "btn btn-$type");

        if ($name === NULL AND $value === NULL) {
            return Bootstrap::base('div', $text, $variables, $attributes);
        }

        if ($name === NULL) {
            return HTML::anchor($value, __($text, $variables), $attributes);
        }

        $attributes['value'] = $value;

        return Form::button($name, $text, $attributes);
    }

    /**
     * Generates a caret.
     * 
     * @param  array $attributes
     * @return string
     */
    public static function caret(array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'caret');

        return Bootstrap::base('span', '', NULL, $attributes);
    }

    /**
     * Generates a Bootstrap carousel slideshow.
     * 
     * @link
     * 
     * @param string $id is the id to identify the carousel.
     * @param array $elements are the elements shown in the carousel.
     * @param array|string $actives are the active elements.
     * @param array $attributes 
     * @return View
     */
    public static function carousel($id, array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        if ($actives === NULL) {

            $actives = array();

            // Le premier élément est actif
            if (Valid::not_empty($elements)) {
                $keys = array_keys($elements);
                $actives[] = array_shift($keys);
            }
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        foreach ($elements as $key => &$element) {
            $item_attributes = array('class' => 'item');

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($item_attributes, 'class', 'active');
            }

            $element = Bootstrap::base('div', (string) $element, $variables, $item_attributes);
        }

        Bootstrap::add_attribute($attributes, 'class', 'carousel slide');

        $attributes['id'] = $id === NULL ? uniqid('carousel-') : $id;

        $parameters = array();
        $parameters['elements'] = $elements;
        $parameters['actives'] = $actives;
        $parameters['variables'] = $variables;
        $parameters['attributes'] = $attributes;

        return View::factory('bootstrap/carousel', $parameters);
    }

    /**
     * Generates a Bootstrap close button.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#misc
     * 
     * @param string $text
     * @param array $attributes
     * @return type
     */
    public static function close(array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'close');

        // Fix for iPhone
        $attributes['href'] = Arr::get($attributes, 'href', '#');

        return Bootstrap::button('&times;', NULL, NULL, '', $attributes);
    }

    /**
     * Generates a basic dropdown menu. This function supports recursivity for
     * sub-menues.
     * 
     * For sub-menues, the key will be the text shown and the value has to be
     * a valid $elements array for recursivity. There is no convenient way to
     * make clickable parent menus. (However, if you have suggestions, go on!)
     * 
     * @todo clickable menus with child
     * 
     * @link http://twitter.github.com/bootstrap/components.html#dropdowns
     * 
     * @param array $elements elements to include in the dropdown.
     * @param variant $actives actives elements.
     * @param array $attributes attributes for the dropdown.
     * @return string
     */
    public static function dropdown(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        if ($actives === NULL) {
            $actives = array();
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        Bootstrap::add_attribute($attributes, 'class', 'dropdown-menu');

        $output = '<ul' . HTML::attributes($attributes) . '>';

        foreach ($elements as $key => $element) {

            $atts = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($atts, 'class', 'active');
            }

            if (Arr::is_array($element)) {
                // Creating submenu
                Bootstrap::add_attribute($atts, 'class', 'dropdown-submenu');
                $output .= '<li' . HTML::attributes($atts) . '>' . $key . Bootstrap::dropdown($element, $actives, $variables, $attributes) . '</li>';
            } else {
                $output .= Bootstrap::base('li', $element, $variables, $atts);
            }
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Generates a Bootstrap dropdown button.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#buttonDropdowns
     * 
     * @param type $title title for the dropdown button.
     * @param array $elements elements to include in the dropdown button
     * @param array $type 
     * @param array $attributes custom attributes for the button group.
     * @return string rendered HTML Code to create the button
     */
    public static function dropdown_button($title, array $elements, $actives = NULL, array $variables = NULL, $type = '', array $attributes = NULL, array $button_attributes = NULL, array $dropdown_attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'btn-group');

        Bootstrap::add_attribute($button_attributes, 'class', 'dropdown-toggle');

        $button_attributes['data-toggle'] = 'dropdown';

        $button = Bootstrap::button($title . ' ' . Bootstrap::caret(), NULL, NULL, $type, $variables, $button_attributes);

        $dropdown = Bootstrap::dropdown($elements, $actives, $variables, $dropdown_attributes);

        return '<div' . HTML::attributes($attributes) . '>' . $button . $dropdown . '</div>';
    }

    /**
     * Glyphicon generator.
     * 
     * @link http://twitter.github.com/bootstrap/base-css.html#icons
     * 
     * @param string $name is the icon name without the "icon-" prefix.
     * @param array $attributes are any extra attributes to add to the i tag.
     * @return string html code for the icon.
     */
    public static function icon($name, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "icon-$name");

        return Bootstrap::base('i', NULL, NULL, $attributes);
    }

    /**
     * Generates a Bootstrap label.
     * 
     * @link 
     * 
     * @param string $message
     * @param array $variables
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function label($message, array $variables = NULL, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "label label-$type");

        return Bootstrap::base('span', $message, $variables, $attributes);
    }

    /**
     * Generates a Bootstrap modal.
     * 
     * @link http://twitter.github.com/bootstrap/javascript.html#modals
     * 
     * @param string $id is an unique id to identify the modal and trigger it.
     * @param type $title is the title of the modal.
     * @param type $description is the description of the modal.
     * @param string $action is the form action, if appliable.
     * @param type $save save button.
     * @param type $cancel cancel button.
     * @param array $attributes attributs du modal.     
     * @param array $parameters are the parameters passed to the view.
     * @return View
     */
    public static function modal($id, $title, $description, $action = NULL, $save = NULL, $close = NULL, array $variables = NULL, array $attributes = NULL, array $parameters = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'modal hide fade');

        $attributes['id'] = $id;
        $parameters['title'] = (string) $title;
        $parameters['description'] = (string) $description;
        $parameters['action'] = (string) $action;
        $parameters['save'] = (string) $save;
        $parameters['close'] = (string) $close;
        $parameters['variables'] = $variables;
        $parameters['attributes'] = $attributes;

        return View::factory('bootstrap/modal', $parameters);
    }

    /**
     * Generates basic navs.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#navs
     * 
     * @param array $elements
     * @param variant $actives
     * @param array $attributes
     * @param array $sub_attributes attributes passed to sub navs.
     * @return string
     */
    public static function nav(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL, array $sub_attributes = NULL, array $li_attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'nav');

        if ($actives === NULL) {
            $actives = array();
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        $output = '<ul' . HTML::attributes($attributes) . '>';

        foreach ($elements as $key => $element) {

            $_li_attributes = $li_attributes;

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($_li_attributes, 'class', 'active');
            }

            if (Arr::is_array($element)) {
                $output .= '<li' . HTML::attributes($_li_attributes) . '>';
                $output .= Bootstrap::nav($element, $actives, $variables, $sub_attributes);
                $output .= '</li>';
            } else {
                $output .= Bootstrap::base('li', $element, $variables, $_li_attributes);
            }
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Generates a Bootstrap navigation list.
     * 
     * @link 
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_list(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'nav-list');

        return Bootstrap::nav($elements, $actives, $variables, $attributes);
    }

    /**
     * Generates Bootstrap navigation pills.
     * 
     * @link
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_pills(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL, array $sub_attributes = NULL, array $li_attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'nav-pills');

        // Subnavs are stacked
        Bootstrap::add_attribute($sub_attributes, 'class', 'nav-pills nav-stacked');

        return Bootstrap::nav($elements, $actives, $variables, $attributes, $sub_attributes, $li_attributes);
    }

    /**
     * Generates Bootstrap navigation tabs.
     * 
     * @link
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_tabs(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'nav-tabs');

        return Bootstrap::nav($elements, $actives, $variables, $attributes);
    }

    /**
     * Generate a bootstrap pagination given links.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#pagination
     * 
     * @param array $elements
     * @param variant $active active element or elements.
     * @param array $attributes attributes applied to pagination div.
     * @return string
     */
    public static function pagination(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        if ($actives === NULL) {
            $actives = array();
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        Bootstrap::add_attribute($attributes, 'class', 'pagination');

        $output = '<div' . HTML::attributes($attributes) . '><ul>';

        foreach ($elements as $key => $value) {

            $li_attributes = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($li_attributes, 'class', 'active');
            }

            $output .= Bootstrap::base('li', $value, $variables, $li_attributes);
        }

        $output .= '</ul></div>';

        return $output;
    }

    /**
     * Generates a Bootstrap progress bar.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#progress
     * 
     * @param string $message
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function progress($progress, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', "progress progress-$type");

        $output = '<div' . HTML::attributes($attributes) . '>';

        $atts = array('class' => 'bar');

        // Support for multiple progress bars
        if (Arr::is_array($progress)) {
            foreach ($progress as $p) {
                $atts['style'] = "width: $p%;";
                $output .= '<div' . HTML::attributes($atts) . '/>';
            }
        } else {
            $atts['style'] = "width: $progress%;";
            $output .= '<div' . HTML::attributes($atts) . '/>';
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Generates a Bootstrap well.
     * 
     * @link http://twitter.github.com/bootstrap/components.html#misc
     * 
     * @param string $message is the content to be presented in a well.
     * @param string $size is the size which could be small or large.
     * @param array $attributes is an array of extra attributes to apply on the
     * well.
     * @return string
     */
    public static function well($message, array $variables = NULL, $size = '', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'class', 'well');
        Bootstrap::add_attribute($attributes, 'class', "well-$size");

        return Bootstrap::base('div', $message, $variables, $attributes);
    }

}

?>
