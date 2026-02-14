<?php
/**
 * 404 Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--404" role="main">
    <div class="content-area content-area--narrow error-404-page" data-jk-reveal>

        <header class="page-header">
            <?php jackrabbit_the_breadcrumbs(); ?>
            <span class="error-code">404</span>
            <h1 class="page-title">
                <?php esc_html_e('Page Not Found', 'jackrabbit'); ?>
            </h1>
        </header>

        <div class="page-content">
            <p>
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'jackrabbit'); ?>
            </p>

            <?php get_search_form(); ?>

            <div class="error-404-widgets">
                <div class="error-404-widget">
                    <h3>
                        <?php esc_html_e('Recent Posts', 'jackrabbit'); ?>
                    </h3>
                    <ul>
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 5,
                            'post_status' => 'publish',
                        ));
                        foreach ($recent_posts as $post):
                            ?>
                            <li><a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                                    <?php echo esc_html($post['post_title']); ?>
                                </a></li>
                        <?php endforeach;
                        wp_reset_postdata(); ?>
                    </ul>
                </div>

                <div class="error-404-widget">
                    <h3>
                        <?php esc_html_e('Categories', 'jackrabbit'); ?>
                    </h3>
                    <ul>
                        <?php
                        wp_list_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'show_count' => 1,
                            'title_li' => '',
                            'number' => 10,
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        </div><!-- .page-content -->

    </div><!-- .content-area -->
</main><!-- #main -->

<?php
get_footer();
