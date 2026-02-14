<?php
/**
 * Performance Module
 *
 * Removes WordPress bloat and optimizes front-end delivery.
 *
 * @package Jackrabbit
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Remove WordPress emoji scripts and styles.
 */
function jackrabbit_remove_emoji()
{
    if ('1' !== jackrabbit_get_option('remove_emoji', '1')) {
        return;
    }

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('tiny_mce_plugins', function ($plugins) {
        return is_array($plugins) ? array_diff($plugins, array('wpemoji')) : array();
    });

    add_filter('wp_resource_hints', function ($urls, $relation_type) {
        if ('dns-prefetch' === $relation_type) {
            $emoji_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
            $urls = array_filter($urls, function ($url) use ($emoji_url) {
                return false === strpos($url, $emoji_url);
            });
        }
        return $urls;
    }, 10, 2);
}
add_action('init', 'jackrabbit_remove_emoji');

/**
 * Clean up <head> — remove RSD, WLW, shortlinks, REST API link, WP generator.
 */
function jackrabbit_cleanup_head()
{
    if ('1' !== jackrabbit_get_option('cleanup_head', '1')) {
        return;
    }

    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('after_setup_theme', 'jackrabbit_cleanup_head');

/**
 * Remove jQuery Migrate on the front-end.
 */
function jackrabbit_remove_jquery_migrate($scripts)
{
    if (is_admin()) {
        return;
    }

    if ('1' !== jackrabbit_get_option('remove_jquery_migrate', '1')) {
        return;
    }

    if (isset($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            array('jquery-migrate')
        );
    }
}
add_action('wp_default_scripts', 'jackrabbit_remove_jquery_migrate');

/**
 * Add resource hints — preconnect to Google Fonts, dns-prefetch common CDNs.
 */
function jackrabbit_resource_hints($urls, $relation_type)
{
    $options = get_option('jackrabbit_options', array());
    $font_family = isset($options['font_family']) ? $options['font_family'] : 'inter';

    if ('preconnect' === $relation_type && 'system' !== $font_family) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => true,
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'jackrabbit_resource_hints', 10, 2);

/**
 * Disable self-pingbacks.
 */
function jackrabbit_disable_self_pingbacks(&$links)
{
    $home = get_option('home');
    foreach ($links as $key => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$key]);
        }
    }
}
add_action('pre_ping', 'jackrabbit_disable_self_pingbacks');

/**
 * Add 'defer' to non-critical scripts.
 */
function jackrabbit_defer_scripts($tag, $handle, $src)
{
    // Don't defer in admin or for these essential handles.
    if (is_admin()) {
        return $tag;
    }

    $no_defer = array('jquery-core', 'wp-polyfill');
    if (in_array($handle, $no_defer, true)) {
        return $tag;
    }

    // Skip if already deferred or async.
    if (false !== strpos($tag, 'defer') || false !== strpos($tag, 'async')) {
        return $tag;
    }

    return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'jackrabbit_defer_scripts', 10, 3);
