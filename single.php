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
    <div class="single-post-layout">

        <div class="single-post-layout__content">
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
        </div><!-- .single-post-layout__content -->

        <aside class="single-post-layout__sidebar" role="complementary" aria-label="<?php esc_attr_e('Post Sidebar', 'jackrabbit'); ?>">
            <?php if (is_active_sidebar('sidebar-post')): ?>
                <?php dynamic_sidebar('sidebar-post'); ?>
            <?php else: ?>
                <?php // Default sidebar widgets when no widgets are configured. ?>
                <section class="widget widget_about_post">
                    <h3 class="widget-title"><?php esc_html_e('About the Author', 'jackrabbit'); ?></h3>
                    <div class="widget-author-card">
                        <div class="widget-author-card__avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 64); ?>
                        </div>
                        <div class="widget-author-card__info">
                            <a class="widget-author-card__name" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php the_author(); ?>
                            </a>
                            <?php
                            $author_desc = get_the_author_meta('description');
                            if (!empty($author_desc)):
                                ?>
                                <p class="widget-author-card__bio"><?php echo esc_html(wp_trim_words($author_desc, 20)); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <?php
                $sidebar_cats = get_the_category();
                if (!empty($sidebar_cats)):
                    ?>
                    <section class="widget widget_categories_related">
                        <h3 class="widget-title"><?php esc_html_e('Categories', 'jackrabbit'); ?></h3>
                        <ul>
                            <?php foreach ($sidebar_cats as $cat): ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                                        <?php echo esc_html($cat->name); ?>
                                    </a>
                                    <span class="widget-count"><?php echo esc_html($cat->count); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>

                <?php
                $sidebar_tags = get_the_tags();
                if (!empty($sidebar_tags)):
                    ?>
                    <section class="widget widget_tag_cloud_post">
                        <h3 class="widget-title"><?php esc_html_e('Tags', 'jackrabbit'); ?></h3>
                        <div class="tagcloud">
                            <?php foreach (array_slice($sidebar_tags, 0, 12) as $tag): ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php
                $recent_posts = new WP_Query(array(
                    'post_type'      => 'post',
                    'posts_per_page' => 5,
                    'post__not_in'   => array(get_the_ID()),
                    'no_found_rows'  => true,
                ));
                if ($recent_posts->have_posts()):
                    ?>
                    <section class="widget widget_recent_entries">
                        <h3 class="widget-title"><?php esc_html_e('Recent Posts', 'jackrabbit'); ?></h3>
                        <ul>
                            <?php
                            while ($recent_posts->have_posts()):
                                $recent_posts->the_post();
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <span class="post-date"><?php echo esc_html(get_the_date()); ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </section>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>
            <?php endif; ?>
        </aside><!-- .single-post-layout__sidebar -->

    </div><!-- .single-post-layout -->
</main><!-- #main -->

<?php
get_footer();
