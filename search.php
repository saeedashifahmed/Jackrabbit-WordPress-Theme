<?php
/**
 * Search Results Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--search" role="main">
    <div class="content-area">

        <header class="page-header" data-jk-reveal>
            <?php jackrabbit_the_breadcrumbs(); ?>
            <h1 class="page-title">
                <?php printf(esc_html__('Search Results for: %s', 'jackrabbit'), '<span>' . esc_html(get_search_query()) . '</span>'); ?>
            </h1>
        </header>

        <?php if (have_posts()): ?>
            <?php jackrabbit_render_archive_toolbar(); ?>
            <div class="posts-grid">
                <?php
                while (have_posts()):
                    the_post();
                    get_template_part('template-parts/content', 'search');
                endwhile;
                ?>
            </div>

            <nav class="pagination" aria-label="<?php esc_attr_e('Search Results Navigation', 'jackrabbit'); ?>">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '&larr; ' . esc_html__('Previous', 'jackrabbit'),
                    'next_text' => esc_html__('Next', 'jackrabbit') . ' &rarr;',
                ));
                ?>
            </nav>
        <?php else: ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>

    </div><!-- .content-area -->
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
