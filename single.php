<?php
/**
 * Single Post Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--single" role="main">
    <div class="content-area content-area--narrow">

        <?php
        while (have_posts()):
            the_post();

            get_template_part('template-parts/content', 'single');

            // Post navigation.
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous Post', 'jackrabbit') . '</span><span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next Post', 'jackrabbit') . '</span><span class="nav-title">%title</span>',
            ));

            // Comments.
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;

        endwhile;
        ?>

    </div><!-- .content-area -->
</main><!-- #main -->

<?php
get_footer();
