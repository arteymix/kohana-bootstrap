<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper class for Bootstrap.
 * 
 * This is designed as generic as possible. Inputs are safed against XSS and
 * other security breaches on Kohana layer.
 * 
 * @see http://twitter.github.com/bootstrap/
 * 
 * @package Bootstrap
 * @category Helpers
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Bootstrap {

    const CARET = '<span class="caret"></span>',
            SUCCESS = 'success',
            INFO = 'info',
            WARNING = 'warning',
            ERROR = 'error';

    public static $types = array(
        'success',
        'info',
        'warning',
        'error'
    );

    /**
     * Append an attributes without overriding existing attributes. class is
     * used by default.
     * 
     * @param array $attributes array of key-value pairs of attributes
     * @param type $value value to append
     * @param type $name name of the attribute
     */
    public static function add_attribute(&$attributes, $value, $name = "class") {
        $attributes[$name] = Arr::get($attributes, $name, "") . " " . $value;
    }

    /**
     * 
     * @param string $tag
     * @param string $body
     * @param array $variables
     * @param array $attributes
     * @return string
     */
    public static function base($tag, $body, array $variables = NULL, array $attributes = NULL) {
        return "<$tag" . HTML::attributes($attributes) . '>' . __($body, $variables) . "</$tag>";
    }

    /**
     * Generates a Bootstrap alert.
     * 
     * @see 
     * 
     * @param string $message
     * @param array $variables
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function alert($message, array $variables = NULL, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "alert alert-$type");

        return Bootstrap::base('div', $message, $variables, $attributes);
    }

    /**
     * Generates a Bootstrap badge.
     * 
     * @see 
     * 
     * @param string $message
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function badge($message, array $variables = NULL, $type = 'info', $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "badge badge-$type");

        return Bootstrap::base('span', $message, $variables, $attributes);
    }

    /**
     * Generate a Bootstrap breadcrumb.
     * 
     * @see http://twitter.github.com/bootstrap/components.html#breadcrumbs
     * 
     * @param array $elements
     * @param variant $actives
     * @param array $variables
     * @param string $divider
     * @param array $attributes
     * @return string
     */
    public static function breadcrumb(array $elements, $actives = NULL, array $variables = NULL, $divider = '/', array $attributes = NULL) {

        if ($actives === NULL) {
            $actives = array();
        }

        if (!Arr::is_array($actives)) {
            $actives = array($actives);
        }

        Bootstrap::add_attribute($attributes, 'breadcrumb');

        $output = '<ul' . HTML::attributes($attributes) . '>';

        $count = 0;

        foreach ($elements as $key => $element) {

            $count++;

            // Check if we add a divider
            if ($count < count($elements)) {
                $element .= Bootstrap::base('span', $divider, $variables, array('class' => 'divider'));
            }

            $attr = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($attr, 'active');
            }

            $output .= Bootstrap::base('li', $element, $variables);
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * Generate a Bootstrap button.
     * 
     * Basically, it makes a simple button. However, if only a string $value is
     * specified ($name being NULL), it will make an anchor button.
     * 
     * @param string $text text to show
     * @param string $name name in a form
     * @param string $value value in a form or href if $name is NULL
     * @param string $type 
     * @param array $attributes 
     * @return string
     */
    public static function button($text, $name = NULL, $value = NULL, $type = '', array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "btn btn-$type");

        $attributes['name'] = $name;
        $attributes['value'] = $value;

        $tag = 'button';

        if ($name === NULL && is_string($value)) {
            // It's a link button
            $tag = 'a';
            $attributes['href'] = URL::site($value);
        }

        return Bootstrap::base($tag, $text, $variables, $attributes);
    }

    /**
     * Generates a Bootstrap carousel slideshow.
     * 
     * @see
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

        Bootstrap::add_attribute($attributes, 'carousel slide');

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
     * @see http://twitter.github.com/bootstrap/components.html#misc
     * 
     * @param string $text
     * @param array $attributes
     * @return type
     */
    public static function close(array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'close');

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
     * @see http://twitter.github.com/bootstrap/components.html#dropdowns
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

        Bootstrap::add_attribute($attributes, "dropdown-menu");

        $output = '<ul' . HTML::attributes($attributes) . '>';

        foreach ($elements as $key => $element) {

            $atts = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($atts, 'active');
            }

            if (Arr::is_array($element)) {
                // Creating submenu
                Bootstrap::add_attribute($atts, 'dropdown-submenu');
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
     * @see http://twitter.github.com/bootstrap/components.html#buttonDropdowns
     * 
     * @param type $title title for the dropdown button.
     * @param array $elements elements to include in the dropdown button
     * @param array $type 
     * @param array $attributes custom attributes for the button group.
     * @return string rendered HTML Code to create the button
     */
    public static function dropdown_button($title, array $elements, $actives = NULL, array $variables = NULL, $type = '', array $attributes = NULL, array $button_attributes = NULL, array $dropdown_attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'btn-group');

        Bootstrap::add_attribute($button_attributes, 'dropdown-toggle');

        $button_attributes['data-toggle'] = 'dropdown';

        $output = '<div' . HTML::attributes($attributes) . '>';

        $output .= Bootstrap::button($title . ' ' . Bootstrap::CARET, NULL, NULL, $type, $variables, $button_attributes);

        $output .= Bootstrap::dropdown($elements, $actives, $variables, $dropdown_attributes);

        $output .= '</div>';

        return $output;
    }

    /**
     * Glyphicon generator.
     * 
     * @see http://twitter.github.com/bootstrap/base-css.html#icons
     * 
     * @param string $name is the icon name without the "icon-" prefix.
     * @param array $attributes are any extra attributes to add to the i tag.
     * @return string html code for the icon.
     */
    public static function icon($name, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "icon-$name");

        return Bootstrap::base('i', NULL, NULL, $attributes);
    }

    /**
     * Generates a Bootstrap label.
     * 
     * @see 
     * 
     * @param string $message
     * @param array $variables
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function label($message, array $variables = NULL, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "label label-$type");

        return Bootstrap::base('span', $message, $variables, $attributes);
    }

    /**
     * Generates a Bootstrap modal.
     * 
     * @see http://twitter.github.com/bootstrap/javascript.html#modals
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

        Bootstrap::add_attribute($attributes, 'modal hide fade');

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
     * @see http://twitter.github.com/bootstrap/components.html#navs
     * 
     * @param array $elements
     * @param variant $actives
     * @param array $attributes
     * @param array $sub_attributes attributes passed to sub navs.
     * @return string
     */
    public static function nav(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL, array $sub_attributes = NULL, array $li_attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'nav');

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
                Bootstrap::add_attribute($_li_attributes, 'active');
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
     * Generates a navigation bar.
     * 
     * @see
     * 
     * @param type $brand
     * @param array $elements
     * @param type $actives
     * @param type $attributes
     * @return string
     */
    public static function navbar(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL, array $nav_attributes = NULL) {

        Bootstrap::add_attribute($attributes, "navbar");

        $output = '<div' . HTML::attributes($attributes) . '>';

        $output .= '<div' . HTML::attributes(array('class' => 'navbar-inner')) . '>';

        $output .= Bootstrap::nav($elements, $actives, $variables, $nav_attributes);

        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    /**
     * Generates a Bootstrap navigation list.
     * 
     * @see 
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_list(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "nav-list");

        return Bootstrap::nav($elements, $actives, $variables, $attributes);
    }

    /**
     * Generates Bootstrap navigation pills.
     * 
     * @see
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_pills(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL, array $sub_attributes = NULL, array $li_attributes = NULL) {

        Bootstrap::add_attribute($attributes, "nav-pills");

        // Subnavs are stacked
        Bootstrap::add_attribute($sub_attributes, 'nav-pills nav-stacked');

        return Bootstrap::nav($elements, $actives, $variables, $attributes, $sub_attributes, $li_attributes);
    }

    /**
     * Generates Bootstrap navigation tabs.
     * 
     * @see
     * 
     * @param array $elements array of uri => name 
     * @param array|string $actives active tabs
     * @param array $attributes
     * @return type
     */
    public static function nav_tabs(array $elements, $actives = NULL, array $variables = NULL, array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'nav-tabs');

        return Bootstrap::nav($elements, $actives, $variables, $attributes);
    }

    /**
     * Generate a bootstrap pagination given links.
     * 
     * @see http://twitter.github.com/bootstrap/components.html#pagination
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

        Bootstrap::add_attribute($attributes, 'pagination');

        $output = '<div' . HTML::attributes($attributes) . '><ul>';

        foreach ($elements as $key => $value) {

            $li_attributes = array();

            if (in_array($key, $actives)) {
                Bootstrap::add_attribute($li_attributes, 'active');
            }

            $output .= Bootstrap::base('li', $value, $variables, $li_attributes);
        }

        $output .= '</ul></div>';

        return $output;
    }

    /**
     * Generates a Bootstrap progress bar.
     * 
     * @see http://twitter.github.com/bootstrap/components.html#progress
     * 
     * @param string $message
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public static function progress($progress, $type = 'info', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, "progress progress-$type");

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
     * Bootstrap well implementation.
     * 
     * @see http://twitter.github.com/bootstrap/components.html#misc
     * 
     * @param string $message is the content to be presented in a well.
     * @param string $size is the size which could be small or large.
     * @param array $attributes is an array of extra attributes to apply on the
     * well.
     * @return string
     */
    public static function well($message, array $variables = NULL, $size = '', array $attributes = NULL) {

        Bootstrap::add_attribute($attributes, 'well');
        Bootstrap::add_attribute($attributes, "well-$size");

        return Bootstrap::base('div', $message, $variables, $attributes);
    }

}

?>
