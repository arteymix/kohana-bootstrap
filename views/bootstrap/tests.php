<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<h1>Tests for Kohana Bootstrap module</h1>

<section id="bootstrap-alert">
    <h2>Alert</h2>
    <?php echo Bootstrap::alert('Foo') ?>
</section>

<section id="bootstrap-badge">
    <h2>Badge</h2>
    <?php echo Bootstrap::badge('Foo') ?>
</section>

<section id="bootstrap-breadcrumb">
    <h2>Breadcrumb</h2>
    <?php echo Bootstrap::breadcrumb(array('Foo', 'Bar')) ?>
</section>

<section id="bootstrap-button">
    <h2>Button</h2>
    <?php echo Bootstrap::button('div') ?>
    <?php echo Bootstrap::button('a', NULL, 'http://www.example.com') ?>
    <?php echo Bootstrap::button('button', 'name', 'value') ?>

</section>

<section id="bootstrap-carousel">
    <h2>Carousel</h2>
    <?php echo Bootstrap::carousel('Foo', array('plenty of', 'foos', 'everywhere')) ?>
</section>

<section id="bootstrap-close">
    <h2>Close</h2>
    <?php echo Bootstrap::close() ?>
</section>

<section id="bootstrap-dropdown_button">
    <h2>Dropdown button</h2>
    <?php echo Bootstrap::dropdown_button('Foo', array(HTML::anchor('foo'))) ?>
</section>

<section id="bootstrap-icon">
    <h2>Icon</h2>
    <?php echo Bootstrap::icon('minus') ?>
</section>

<section id="bootstrap-label">
    <h2>Label</h2>
    <?php echo Bootstrap::label('Foo') ?>
</section>

<section id="bootstrap-modal">
    <h2>Modal</h2>
    <?php echo Bootstrap::modal('id', 'Foo', 'Beautiful bar') ?>
</section>

<section id="bootstrap-nav">
    <h2>Navigation</h2>
    <?php echo Bootstrap::nav(array(HTML::anchor('foo'), HTML::anchor('bar'))) ?>
</section>

<section id="bootstrap-nav_list">
    <h2>Navigation list</h2>
    <?php echo Bootstrap::nav_list(array(HTML::anchor('foo'), HTML::anchor('bar'))) ?>
</section>

<section id="bootstrap-nav_pills">
    <h2>Navigation pills</h2>
    <?php echo Bootstrap::nav_pills(array(HTML::anchor('foo'), HTML::anchor('bar'))) ?>
</section>

<section id="bootstrap-nav_tabs">
    <h2>Navigation tabs</h2>
    <?php echo Bootstrap::nav_tabs(array(HTML::anchor('foo'), HTML::anchor('bar'))) ?>
</section>

<section id="bootstrap-pagination">
    <h2>Pagination</h2>
    <?php echo Bootstrap::pagination(array(HTML::anchor('1'), HTML::anchor('2'))) ?>
</section>

<section id="bootstrap-progress">
    <h2>Progress</h2>
    <?php echo Bootstrap::progress(33) ?>
</section>

<section id="bootstrap-well">
    <h2>Well</h2>
    <?php echo Bootstrap::well('LOL', NULL, 'small') ?>
</section>




