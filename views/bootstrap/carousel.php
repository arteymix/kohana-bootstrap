<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div<?php echo HTML::attributes($attributes) ?>>
    <!-- Carousel items -->
    <div class="carousel-inner">
        <?php foreach ($elements as $element) : ?>
            <?php echo $element ?>
        <?php endforeach; ?>
    </div>
    <!-- Carousel nav -->
    <?php if ($elements): ?>
        <?php echo HTML::anchor('#' . $attributes['id'], '&lsaquo;', array('class' => 'carousel-control left', 'data-slide' => 'prev')) ?>
        <?php echo HTML::anchor('#' . $attributes['id'], '&rsaquo;', array('class' => 'carousel-control right', 'data-slide' => 'next')) ?>
    <?php endif; ?>
</div>
