<?php
/**
 * Template Part: Content – Search Result
 *
 * Compact card layout for search-results pages.
 * Highlights the search query inside the excerpt.
 *
 * @package Jackrabbit
 * @since   1.2.0
 */

defined('ABSPATH') || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card post-card--search'); ?> data-jk-reveal>

    <?php if (has_post_thumbnail()): ?>
        <div class="post-card__thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-card__body">
        <header class="post-card__header">
            <div class="post-card__meta">
                <span class="post-card__type">
                    <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                </span>
                <span class="post-card__sep">&middot;</span>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="post-card__date">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
                <?php
                $categories   = get_the_category();
                $reading_time = jackrabbit_get_reading_time(get_the_ID());
                if (!empty($categories)):
                    ?>
                    <span class="post-card__sep">&middot;</span>
                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"
                        class="post-card__category">
                        <?php echo esc_html($categories[0]->name); ?>
                    </a>
                <?php endif; ?>
                <span class="post-card__sep">&middot;</span>
                <span class="post-card__reading-time">
                    <?php
                    printf(
                        esc_html(_n('%d min read', '%d min read', $reading_time, 'jackrabbit')),
                        $reading_time
                    );
                    ?>
                </span>
            </div>

            <?php the_title('<h2 class="post-card__title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
        </header>

        <div class="post-card__excerpt">
            <?php
            $excerpt = get_the_excerpt();
            $query   = get_search_query();

            if ($query) {
                // Highlight the search query term(s) inside the excerpt.
                $highlighted = preg_replace(
                    '/(' . preg_quote($query, '/') . ')/iu',
                    '<mark class="search-highlight">$1</mark>',
                    esc_html($excerpt)
                );
                echo wp_kses($highlighted, array('mark' => array('class' => array())));
            } else {
                echo esc_html($excerpt);
            }
            ?>
        </div>

        <footer class="post-card__footer">
            <span class="post-card__author">
                <?php
                printf(
                    esc_html__('By %s', 'jackrabbit'),
                    '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
                );
                ?>
            </span>
            <a href="<?php the_permalink(); ?>" class="post-card__read-more">
                <?php esc_html_e('Read More →', 'jackrabbit'); ?>
            </a>
        </footer>
    </div><!-- .post-card__body -->

</article>
