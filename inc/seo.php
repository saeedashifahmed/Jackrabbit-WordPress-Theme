<?php
/**
 * SEO Module
 *
 * Outputs structured data (JSON-LD), Open Graph, and Twitter Card meta tags.
 * All features are toggled via Theme Panel options.
 *
 * Intentionally lightweight — defers to dedicated SEO plugins when detected.
 *
 * @package Jackrabbit
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Check if a major SEO plugin is active.
 */
function jackrabbit_seo_plugin_active()
{
    return (
        defined('WPSEO_VERSION') ||              // Yoast SEO
        defined('RANK_MATH_VERSION') ||           // Rank Math
        class_exists('AIOSEO\\Plugin\\AIOSEO')   // All in One SEO
    );
}

/**
 * Output JSON-LD Structured Data.
 */
function jackrabbit_jsonld_output()
{
    if ('1' !== jackrabbit_get_option('enable_jsonld', '1')) {
        return;
    }

    // Skip if an SEO plugin handles structured data.
    if (jackrabbit_seo_plugin_active()) {
        return;
    }

    $schema = array();

    // WebSite schema (homepage / archives).
    if (is_front_page() || is_home()) {
        $schema[] = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ),
        );
    }

    // Article schema (single posts).
    if (is_singular('post')) {
        global $post;

        $article = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
            ),
        );

        // Featured image.
        if (has_post_thumbnail($post->ID)) {
            $img_url = get_the_post_thumbnail_url($post->ID, 'large');
            $article['image'] = $img_url;
            $article['publisher']['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $img_url,
            );
        }

        // Description.
        $excerpt = has_excerpt($post->ID) ? get_the_excerpt() : wp_trim_words($post->post_content, 30);
        $article['description'] = wp_strip_all_tags($excerpt);

        // Word count.
        $word_count = str_word_count(wp_strip_all_tags($post->post_content));
        $article['wordCount'] = $word_count;

        $schema[] = $article;
    }

    // Output each schema.
    foreach ($schema as $item) {
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($item, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "\n" . '</script>' . "\n";
    }
}
add_action('wp_head', 'jackrabbit_jsonld_output', 1);

/**
 * Output Open Graph & Twitter Card meta tags.
 */
function jackrabbit_og_tags()
{
    if ('1' !== jackrabbit_get_option('enable_og_tags', '1')) {
        return;
    }

    // Skip if an SEO plugin handles OG tags.
    if (jackrabbit_seo_plugin_active()) {
        return;
    }

    $title = '';
    $description = '';
    $url = '';
    $image = '';
    $type = 'website';

    if (is_singular()) {
        global $post;
        $title = get_the_title();
        $description = has_excerpt($post->ID) ? get_the_excerpt() : wp_trim_words($post->post_content, 30);
        $url = get_permalink();
        $type = ('post' === $post->post_type) ? 'article' : 'website';

        if (has_post_thumbnail($post->ID)) {
            $image = get_the_post_thumbnail_url($post->ID, 'large');
        }
    } elseif (is_front_page() || is_home()) {
        $title = get_bloginfo('name');
        $description = jackrabbit_get_option('meta_description', get_bloginfo('description'));
        $url = home_url('/');
    } elseif (is_archive()) {
        $title = get_the_archive_title();
        $description = get_the_archive_description();
        $url = '';
    }

    if (empty($description)) {
        $description = jackrabbit_get_option('meta_description', get_bloginfo('description'));
    }

    $description = wp_strip_all_tags($description);
    $site_name = get_bloginfo('name');

    // Output tags.
    if ($title) {
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    }

    if ($description) {
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

        // Also output meta description if not singular (singular may have yoast/plugin override).
        if (!is_singular()) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }

    echo '<meta property="og:type" content="' . esc_attr($type) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";

    if ($url) {
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    }

    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    } else {
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }
}
add_action('wp_head', 'jackrabbit_og_tags', 2);

/**
 * Output canonical URL for singular pages.
 */
function jackrabbit_canonical_url()
{
    if (jackrabbit_seo_plugin_active()) {
        return;
    }

    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    } elseif (is_front_page() || is_home()) {
        echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
    }
}
add_action('wp_head', 'jackrabbit_canonical_url', 1);
