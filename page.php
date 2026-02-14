<?php
/**
 * Page Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--page" role="main">
    <div class="content-area content-area--narrow">

        <?php
        while (have_posts()):
            the_post();
            get_template_part('template-parts/content', 'page');

            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
        endwhile;
        ?>

    </div><!-- .content-area -->
</main><!-- #main -->

<?php
get_footer();
