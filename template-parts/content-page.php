<?php
/**
 * Template Part: Content Page
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>

    <header class="page-content__header">
        <?php jackrabbit_the_breadcrumbs(); ?>
        <?php the_title('<h1 class="page-content__title">', '</h1>'); ?>
    </header>

    <?php if (has_post_thumbnail()): ?>
        <figure class="page-content__featured-image">
            <?php the_post_thumbnail('large', array('loading' => 'eager', 'fetchpriority' => 'high')); ?>
        </figure>
    <?php endif; ?>

    <div class="page-content__body entry-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'jackrabbit'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->

</article>
