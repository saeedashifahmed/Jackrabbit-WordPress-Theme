<?php
/**
 * The main template file.
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main" role="main">
    <div class="content-area">

        <?php if (have_posts()): ?>
            <?php
            $featured_post_ids = array();
            $is_first_blog_page = is_home() && !is_paged();
            ?>

            <?php if (is_home() && !is_front_page()): ?>
                <header class="page-header">
                    <h1 class="page-title">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>

            <?php
            if ($is_first_blog_page):
                $featured_query = new WP_Query(array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => 4,
                    'ignore_sticky_posts' => false,
                    'no_found_rows' => true,
                ));

                if ($featured_query->have_posts()):
                    $featured_posts = $featured_query->posts;
                    $featured_post_ids = wp_list_pluck($featured_posts, 'ID');
                    $hero_post = array_shift($featured_posts);

                    if ($hero_post):
                        setup_postdata($hero_post);
                        ?>
                        <section class="home-hero" data-jk-reveal>
                            <article class="home-hero__feature">
                                <?php if (has_post_thumbnail($hero_post->ID)): ?>
                                    <a class="home-hero__media" href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>">
                                        <?php echo get_the_post_thumbnail($hero_post->ID, 'large'); ?>
                                    </a>
                                <?php endif; ?>

                                <div class="home-hero__body">
                                    <p class="home-hero__eyebrow">
                                        <?php esc_html_e('Featured Story', 'jackrabbit'); ?>
                                    </p>
                                    <h2 class="home-hero__title">
                                        <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>">
                                            <?php echo esc_html(get_the_title($hero_post->ID)); ?>
                                        </a>
                                    </h2>
                                    <p class="home-hero__excerpt">
                                        <?php echo esc_html(wp_trim_words(get_the_excerpt($hero_post->ID), 32)); ?>
                                    </p>
                                    <div class="home-hero__meta">
                                        <span>
                                            <?php echo esc_html(get_the_date('', $hero_post->ID)); ?>
                                        </span>
                                        <span aria-hidden="true">&middot;</span>
                                        <span>
                                            <?php
                                            printf(
                                                esc_html(_n('%d min read', '%d min read', jackrabbit_get_reading_time($hero_post->ID), 'jackrabbit')),
                                                jackrabbit_get_reading_time($hero_post->ID)
                                            );
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </article>

                            <?php if (!empty($featured_posts)): ?>
                                <div class="home-hero__secondary">
                                    <?php foreach ($featured_posts as $secondary_post): ?>
                                        <article class="home-hero__secondary-card">
                                            <h3>
                                                <a href="<?php echo esc_url(get_permalink($secondary_post->ID)); ?>">
                                                    <?php echo esc_html(get_the_title($secondary_post->ID)); ?>
                                                </a>
                                            </h3>
                                            <p>
                                                <?php echo esc_html(wp_trim_words(get_the_excerpt($secondary_post->ID), 14)); ?>
                                            </p>
                                            <a class="home-hero__secondary-link"
                                                href="<?php echo esc_url(get_permalink($secondary_post->ID)); ?>">
                                                <?php esc_html_e('Read article', 'jackrabbit'); ?>
                                            </a>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </section>
                        <?php
                        wp_reset_postdata();
                    endif;
                endif;
            endif;
            ?>

            <?php jackrabbit_render_archive_toolbar(); ?>

            <div class="posts-grid">
                <?php
                $has_visible_posts = false;
                while (have_posts()):
                    the_post();

                    if ($is_first_blog_page && in_array(get_the_ID(), $featured_post_ids, true)) {
                        continue;
                    }

                    $has_visible_posts = true;
                    get_template_part('template-parts/content', get_post_type());
                endwhile;

                if (!$has_visible_posts):
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div>

            <nav class="pagination" aria-label="<?php esc_attr_e('Posts Navigation', 'jackrabbit'); ?>">
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
