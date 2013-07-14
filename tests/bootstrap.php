<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * Tests for Bootstrap helper.
 * 
 * @package   Bootstrap
 * @category  Tests
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Bootstrap_Test extends Unittest_TestCase {

    public function test_alert() {
        Bootstrap::alert("test");
    }

    public function test_badge() {
        Bootstrap::badge("test");
    }

    public function test_breadcrumb() {
        Bootstrap::breadcrumb(array());
    }

    public function test_button() {
        Bootstrap::button("test");
    }

    public function test_carousel() {
        Bootstrap::carousel("test", array());
    }

    public function test_close() {
        Bootstrap::close();
    }

    public function test_dropdown_button() {
        Bootstrap::dropdown_button("title", array());
    }

    public function test_dropdown() {
        Bootstrap::dropdown(array());
    }

    public function test_label() {
        Bootstrap::label("test");
    }

    public function test_modal() {
        Bootstrap::modal('id', "title", "description");
    }

    public function test_nav() {
        Bootstrap::nav(array("test"), "", array());
    }

    public function test_nav_list() {
        Bootstrap::nav_list(array("test"), "", array());
    }

    public function test_nav_pills() {
        Bootstrap::nav_pills(array("test"), "", array());
    }

    public function test_nav_tabs() {
        Bootstrap::nav_tabs(array("test"), "", array());
    }

    public function test_pagination() {
        Bootstrap::pagination(array("test"), "", array());
    }

    public function test_progress() {
        Bootstrap::progress("test", "", array());
    }

    public function test_well() {
        Bootstrap::well('Foo');
    }

    public function test_view() {
        View::factory('bootstrap/tests')->render();
    }

}

?>
