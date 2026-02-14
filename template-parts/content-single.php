<?php
/**
 * Template Part: Content Single (Full post)
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

$categories = get_the_category();
$reading_min = jackrabbit_get_reading_time(get_the_ID());
$share_title = rawurlencode(get_the_title());
$share_url = rawurlencode(get_permalink());
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?> data-jk-reveal>

    <header class="single-post__header">
        <?php jackrabbit_the_breadcrumbs(); ?>

        <div class="single-post__meta">
            <?php if (!empty($categories)): ?>
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="single-post__category">
                    <?php echo esc_html($categories[0]->name); ?>
                </a>
            <?php endif; ?>
        </div>

        <?php the_title('<h1 class="single-post__title">', '</h1>'); ?>

        <div class="single-post__byline">
            <div class="single-post__author-avatar">
                <?php echo get_avatar(get_the_author_meta('ID'), 44); ?>
            </div>
            <div class="single-post__author-info">
                <span class="single-post__author-name">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                        <?php the_author(); ?>
                    </a>
                </span>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="single-post__date">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
                <span class="single-post__sep">&middot;</span>
                <span class="single-post__reading-time">
                    <?php
                    printf(
                        esc_html(_n('%d min read', '%d min read', $reading_min, 'jackrabbit')),
                        $reading_min
                    );
                    ?>
                </span>
            </div>
        </div>
    </header>

    <?php if (has_post_thumbnail()): ?>
        <figure class="single-post__featured-image">
            <?php the_post_thumbnail('large', array('loading' => 'eager', 'fetchpriority' => 'high')); ?>
            <?php
            $caption = get_the_post_thumbnail_caption();
            if ($caption):
                ?>
                <figcaption>
                    <?php echo esc_html($caption); ?>
                </figcaption>
            <?php endif; ?>
        </figure>
    <?php endif; ?>

    <div class="single-post__content-wrap">
        <aside class="single-post__toc" data-jk-toc hidden>
            <h2 class="single-post__toc-title">
                <?php esc_html_e('On this page', 'jackrabbit'); ?>
            </h2>
            <ol class="single-post__toc-list"></ol>
        </aside>

        <div class="single-post__content entry-content">
            <?php
            the_content(sprintf(
                wp_kses(
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'jackrabbit'),
                    array('span' => array('class' => array()))
                ),
                get_the_title()
            ));

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'jackrabbit'),
                'after' => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->
    </div>

    <footer class="single-post__footer">
        <?php
        $tags_list = get_the_tag_list('', ', ');
        if ($tags_list):
            ?>
            <div class="single-post__tags">
                <span class="tags-label">
                    <?php esc_html_e('Tags:', 'jackrabbit'); ?>
                </span>
                <?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        <?php endif; ?>

        <div class="single-post__share" aria-label="<?php esc_attr_e('Share this post', 'jackrabbit'); ?>">
            <span class="single-post__share-label">
                <?php esc_html_e('Share', 'jackrabbit'); ?>
            </span>
            <a href="<?php echo esc_url('https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title); ?>"
                target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('X', 'jackrabbit'); ?>
            </a>
            <a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . $share_url); ?>" target="_blank"
                rel="noopener noreferrer">
                <?php esc_html_e('Facebook', 'jackrabbit'); ?>
            </a>
            <a href="<?php echo esc_url('https://www.linkedin.com/sharing/share-offsite/?url=' . $share_url); ?>"
                target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('LinkedIn', 'jackrabbit'); ?>
            </a>
            <button class="single-post__share-copy" type="button" data-copy-link>
                <?php esc_html_e('Copy link', 'jackrabbit'); ?>
            </button>
        </div>
    </footer>

    <?php
    $author_description = get_the_author_meta('description');
    if (!empty($author_description)):
        ?>
        <section class="single-post__author-box" data-jk-reveal>
            <div class="single-post__author-box-avatar">
                <?php echo get_avatar(get_the_author_meta('ID'), 72); ?>
            </div>
            <div class="single-post__author-box-content">
                <p class="single-post__author-box-label">
                    <?php esc_html_e('Written by', 'jackrabbit'); ?>
                </p>
                <h2 class="single-post__author-box-name">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                        <?php the_author(); ?>
                    </a>
                </h2>
                <p class="single-post__author-box-description">
                    <?php echo esc_html($author_description); ?>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $related_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => array(get_the_ID()),
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
    );

    $category_ids = wp_get_post_categories(get_the_ID());
    if (!empty($category_ids)) {
        $related_args['category__in'] = $category_ids;
    }

    $related_query = new WP_Query($related_args);
    if (!$related_query->have_posts() && !empty($category_ids)) {
        unset($related_args['category__in']);
        $related_query = new WP_Query($related_args);
    }

    if ($related_query->have_posts()):
        ?>
        <section class="single-post__related" data-jk-reveal>
            <h2 class="single-post__related-title">
                <?php esc_html_e('Related posts', 'jackrabbit'); ?>
            </h2>
            <div class="single-post__related-grid">
                <?php while ($related_query->have_posts()):
                    $related_query->the_post(); ?>
                    <article class="single-post__related-card">
                        <?php if (has_post_thumbnail()): ?>
                            <a class="single-post__related-image" href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        <?php endif; ?>

                        <div class="single-post__related-body">
                            <h3>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p>
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 14)); ?>
                            </p>
                            <a class="single-post__related-link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Read article', 'jackrabbit'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
        <?php
    endif;
    wp_reset_postdata();
    ?>

</article>
