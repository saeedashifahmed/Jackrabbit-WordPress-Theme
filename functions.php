<?php
/**
 * Jackrabbit Theme Functions
 *
 * @package Jackrabbit
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'JACKRABBIT_VERSION', '1.2.0' );
define( 'JACKRABBIT_DIR', get_template_directory() );
define( 'JACKRABBIT_URI', get_template_directory_uri() );

/*--------------------------------------------------------------
 * Theme Setup
 *--------------------------------------------------------------*/
function jackrabbit_setup() {
    // RSS feed links.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable featured images / post thumbnails.
    add_theme_support( 'post-thumbnails' );

    // Switch core markup to valid HTML5.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Custom logo support.
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Editor styles for Gutenberg.
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/theme.css' );

    // Responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Wide alignment support for Gutenberg.
    add_theme_support( 'align-wide' );

    // Block editor spacing / styles support.
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'wp-block-styles' );

    // Register navigation menus.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'jackrabbit' ),
        'footer'  => esc_html__( 'Footer Menu', 'jackrabbit' ),
    ) );

    // Set content width.
    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 740;
    }
}
add_action( 'after_setup_theme', 'jackrabbit_setup' );

/*--------------------------------------------------------------
 * Register Widget Areas
 *--------------------------------------------------------------*/
function jackrabbit_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'jackrabbit' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in the sidebar.', 'jackrabbit' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets', 'jackrabbit' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in the footer.', 'jackrabbit' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Post Sidebar', 'jackrabbit' ),
        'id'            => 'sidebar-post',
        'description'   => esc_html__( 'Widgets for the single post sidebar. Leave empty for smart defaults.', 'jackrabbit' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'jackrabbit_widgets_init' );

/*--------------------------------------------------------------
 * Enqueue Styles & Scripts
 *--------------------------------------------------------------*/
function jackrabbit_enqueue_assets() {
    // Main theme stylesheet.
    wp_enqueue_style(
        'jackrabbit-style',
        JACKRABBIT_URI . '/assets/css/theme.css',
        array(),
        JACKRABBIT_VERSION
    );

    // Theme JS (deferred, no jQuery dependency).
    wp_enqueue_script(
        'jackrabbit-script',
        JACKRABBIT_URI . '/assets/js/theme.js',
        array(),
        JACKRABBIT_VERSION,
        true
    );

    // Google Fonts — loaded based on Theme Panel setting.
    $options   = get_option( 'jackrabbit_options', array() );
    $font_family = isset( $options['font_family'] ) ? $options['font_family'] : 'inter';

    $google_fonts_map = array(
        'inter'      => 'Inter:wght@400;500;600;700',
        'roboto'     => 'Roboto:wght@400;500;700',
        'outfit'     => 'Outfit:wght@400;500;600;700',
        'lora'       => 'Lora:wght@400;500;600;700',
        'merriweather' => 'Merriweather:wght@400;700',
        'source_serif' => 'Source+Serif+4:wght@400;600;700',
    );

    if ( 'system' !== $font_family && isset( $google_fonts_map[ $font_family ] ) ) {
        wp_enqueue_style(
            'jackrabbit-google-fonts',
            'https://fonts.googleapis.com/css2?family=' . $google_fonts_map[ $font_family ] . '&display=swap',
            array(),
            null
        );
    }

    // Comment reply script.
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'jackrabbit_enqueue_assets' );

/*--------------------------------------------------------------
 * Include Theme Modules
 *--------------------------------------------------------------*/
require_once JACKRABBIT_DIR . '/inc/performance.php';
require_once JACKRABBIT_DIR . '/inc/seo.php';
require_once JACKRABBIT_DIR . '/inc/customizer.php';
require_once JACKRABBIT_DIR . '/inc/class-jackrabbit-theme-panel.php';

/*--------------------------------------------------------------
 * Helper: Get Theme Option
 *--------------------------------------------------------------*/
function jackrabbit_get_option( $key, $default = '' ) {
    $options = get_option( 'jackrabbit_options', array() );
    return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/*--------------------------------------------------------------
 * Output Dynamic CSS Variables
 *--------------------------------------------------------------*/
function jackrabbit_adjust_hex_color( $color, $steps ) {
    $steps = max( -255, min( 255, (int) $steps ) );
    $color = ltrim( (string) $color, '#' );

    if ( 3 === strlen( $color ) ) {
        $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
    }

    if ( 6 !== strlen( $color ) || ! ctype_xdigit( $color ) ) {
        return '#2563eb';
    }

    $red   = max( 0, min( 255, hexdec( substr( $color, 0, 2 ) ) + $steps ) );
    $green = max( 0, min( 255, hexdec( substr( $color, 2, 2 ) ) + $steps ) );
    $blue  = max( 0, min( 255, hexdec( substr( $color, 4, 2 ) ) + $steps ) );

    return sprintf( '#%02x%02x%02x', $red, $green, $blue );
}

function jackrabbit_dynamic_css() {
    $options = get_option( 'jackrabbit_options', array() );

    $accent      = isset( $options['accent_color'] ) ? sanitize_hex_color( $options['accent_color'] ) : '#2563eb';
    $font_size   = isset( $options['base_font_size'] ) ? intval( $options['base_font_size'] ) : 18;
    $max_width   = isset( $options['layout_width'] ) ? intval( $options['layout_width'] ) : 820;
    $font_family = isset( $options['font_family'] ) ? $options['font_family'] : 'inter';
    $scale       = isset( $options['heading_scale'] ) ? (string) $options['heading_scale'] : '1.25';

    if ( empty( $accent ) ) {
        $accent = '#2563eb';
    }

    $valid_scales = array( '1.125', '1.2', '1.25', '1.333', '1.414', '1.5' );
    if ( ! in_array( $scale, $valid_scales, true ) ) {
        $scale = '1.25';
    }

    $font_stack_map = array(
        'system'       => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, sans-serif',
        'inter'        => '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
        'roboto'       => '"Roboto", -apple-system, BlinkMacSystemFont, sans-serif',
        'outfit'       => '"Outfit", -apple-system, BlinkMacSystemFont, sans-serif',
        'lora'         => '"Lora", Georgia, "Times New Roman", serif',
        'merriweather' => '"Merriweather", Georgia, serif',
        'source_serif' => '"Source Serif 4", Georgia, serif',
    );

    $stack           = isset( $font_stack_map[ $font_family ] ) ? $font_stack_map[ $font_family ] : $font_stack_map['inter'];
    $accent_hover    = jackrabbit_adjust_hex_color( $accent, -24 );
    $accent_soft     = jackrabbit_adjust_hex_color( $accent, 165 );
    $base_size_in_rem = max( 0.875, $font_size / 16 );
    $scale_float     = (float) $scale;
    $h6_size         = round( $base_size_in_rem, 3 );
    $h5_size         = round( $h6_size * $scale_float, 3 );
    $h4_size         = round( $h5_size * $scale_float, 3 );
    $h3_size         = round( $h4_size * $scale_float, 3 );
    $h2_size         = round( $h3_size * $scale_float, 3 );
    $h1_size         = round( $h2_size * $scale_float, 3 );

    // Extract RGB triplet from hex accent color for rgba() usage.
    $accent_hex = ltrim( $accent, '#' );
    if ( 3 === strlen( $accent_hex ) ) {
        $accent_hex = $accent_hex[0] . $accent_hex[0] . $accent_hex[1] . $accent_hex[1] . $accent_hex[2] . $accent_hex[2];
    }
    $accent_r = hexdec( substr( $accent_hex, 0, 2 ) );
    $accent_g = hexdec( substr( $accent_hex, 2, 2 ) );
    $accent_b = hexdec( substr( $accent_hex, 4, 2 ) );
    $accent_rgb = "{$accent_r}, {$accent_g}, {$accent_b}";

    echo '<style id="jackrabbit-dynamic-css">
:root {
    --jk-accent: ' . esc_attr( $accent ) . ';
    --jk-accent-rgb: ' . esc_attr( $accent_rgb ) . ';
    --jk-accent-hover: ' . esc_attr( $accent_hover ) . ';
    --jk-accent-soft: ' . esc_attr( $accent_soft ) . ';
    --jk-font-size: ' . esc_attr( $font_size ) . 'px;
    --jk-content-width: ' . esc_attr( $max_width ) . 'px;
    --jk-font-family: ' . $stack . ';
    --jk-heading-scale: ' . esc_attr( $scale ) . ';
    --jk-h1-size: ' . esc_attr( $h1_size ) . 'rem;
    --jk-h2-size: ' . esc_attr( $h2_size ) . 'rem;
    --jk-h3-size: ' . esc_attr( $h3_size ) . 'rem;
    --jk-h4-size: ' . esc_attr( $h4_size ) . 'rem;
    --jk-h5-size: ' . esc_attr( $h5_size ) . 'rem;
    --jk-h6-size: ' . esc_attr( $h6_size ) . 'rem;
}
</style>' . "\n";
}
add_action( 'wp_head', 'jackrabbit_dynamic_css', 5 );

/*--------------------------------------------------------------
 * Theme Helpers
 *--------------------------------------------------------------*/
function jackrabbit_get_reading_time( $post_id = null ) {
    $post = get_post( $post_id ? $post_id : get_the_ID() );

    if ( ! $post instanceof WP_Post ) {
        return 1;
    }

    $content    = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
    $word_count = str_word_count( $content );

    return max( 1, (int) ceil( $word_count / 225 ) );
}

function jackrabbit_get_archive_sort_value() {
    $sort = isset( $_GET['jk_order'] ) ? sanitize_key( wp_unslash( $_GET['jk_order'] ) ) : 'newest';
    $allowed = array( 'newest', 'oldest', 'title_az', 'title_za' );

    return in_array( $sort, $allowed, true ) ? $sort : 'newest';
}

function jackrabbit_apply_archive_sorting( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_feed() || ! ( $query->is_home() || $query->is_archive() || $query->is_search() ) ) {
        return;
    }

    $sort = jackrabbit_get_archive_sort_value();

    if ( 'oldest' === $sort ) {
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'ASC' );
        return;
    }

    if ( 'title_az' === $sort ) {
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
        return;
    }

    if ( 'title_za' === $sort ) {
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'DESC' );
        return;
    }

    $query->set( 'orderby', 'date' );
    $query->set( 'order', 'DESC' );
}
add_action( 'pre_get_posts', 'jackrabbit_apply_archive_sorting' );

function jackrabbit_render_archive_toolbar() {
    if ( ! ( is_home() || is_archive() || is_search() ) ) {
        return;
    }

    global $wp_query;

    $total = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
    $sort  = jackrabbit_get_archive_sort_value();
    ?>
    <div class="archive-toolbar" data-jk-reveal>
        <p class="archive-toolbar__count">
            <?php
            printf(
                esc_html( _n( '%d article', '%d articles', $total, 'jackrabbit' ) ),
                $total
            );
            ?>
        </p>
        <div class="archive-toolbar__controls">
            <div class="archive-view-toggle" role="group" aria-label="<?php esc_attr_e( 'Post layout', 'jackrabbit' ); ?>">
                <button type="button" class="archive-view-toggle__btn is-active" data-view-toggle="grid" aria-pressed="true">
                    <?php esc_html_e( 'Grid', 'jackrabbit' ); ?>
                </button>
                <button type="button" class="archive-view-toggle__btn" data-view-toggle="list" aria-pressed="false">
                    <?php esc_html_e( 'List', 'jackrabbit' ); ?>
                </button>
            </div>

            <form class="archive-sort-form" method="get" aria-label="<?php esc_attr_e( 'Sort posts', 'jackrabbit' ); ?>">
                <label for="jk-order" class="screen-reader-text">
                    <?php esc_html_e( 'Sort posts', 'jackrabbit' ); ?>
                </label>
                <select id="jk-order" name="jk_order" onchange="this.form.submit()">
                    <option value="newest" <?php selected( $sort, 'newest' ); ?>>
                        <?php esc_html_e( 'Newest First', 'jackrabbit' ); ?>
                    </option>
                    <option value="oldest" <?php selected( $sort, 'oldest' ); ?>>
                        <?php esc_html_e( 'Oldest First', 'jackrabbit' ); ?>
                    </option>
                    <option value="title_az" <?php selected( $sort, 'title_az' ); ?>>
                        <?php esc_html_e( 'Title A to Z', 'jackrabbit' ); ?>
                    </option>
                    <option value="title_za" <?php selected( $sort, 'title_za' ); ?>>
                        <?php esc_html_e( 'Title Z to A', 'jackrabbit' ); ?>
                    </option>
                </select>

                <?php
                foreach ( $_GET as $key => $value ) {
                    $safe_key = sanitize_key( wp_unslash( $key ) );
                    if ( empty( $safe_key ) ) {
                        continue;
                    }
                    if ( 'jk_order' === $safe_key || 'submit' === $safe_key || 'paged' === $safe_key ) {
                        continue;
                    }

                    if ( is_array( $value ) ) {
                        continue;
                    }

                    echo '<input type="hidden" name="' . esc_attr( $safe_key ) . '" value="' . esc_attr( wp_unslash( $value ) ) . '">';
                }
                ?>
            </form>
        </div>
    </div>
    <?php
}

function jackrabbit_the_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $items   = array();
    $items[] = array(
        'label' => __( 'Home', 'jackrabbit' ),
        'url'   => home_url( '/' ),
    );

    if ( is_home() ) {
        $posts_page_id = (int) get_option( 'page_for_posts' );

        $items[] = array(
            'label' => $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Blog', 'jackrabbit' ),
            'url'   => '',
        );
    } elseif ( is_single() ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $primary_category = $categories[0];
            $ancestor_ids = array_reverse( get_ancestors( $primary_category->term_id, 'category' ) );

            foreach ( $ancestor_ids as $ancestor_id ) {
                $term = get_term( $ancestor_id, 'category' );
                if ( $term && ! is_wp_error( $term ) ) {
                    $items[] = array(
                        'label' => $term->name,
                        'url'   => get_term_link( $term ),
                    );
                }
            }

            $items[] = array(
                'label' => $primary_category->name,
                'url'   => get_category_link( $primary_category->term_id ),
            );
        }

        $items[] = array(
            'label' => get_the_title(),
            'url'   => '',
        );
    } elseif ( is_page() ) {
        $current_page_id = get_queried_object_id();
        $parent_ids = array_reverse( get_post_ancestors( $current_page_id ) );

        foreach ( $parent_ids as $parent_id ) {
            $items[] = array(
                'label' => get_the_title( $parent_id ),
                'url'   => get_permalink( $parent_id ),
            );
        }

        $items[] = array(
            'label' => get_the_title( $current_page_id ),
            'url'   => '',
        );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();
        if ( $term instanceof WP_Term ) {
            $items[] = array(
                'label' => $term->name,
                'url'   => '',
            );
        }
    } elseif ( is_search() ) {
        $items[] = array(
            'label' => sprintf(
                /* translators: %s: search query. */
                __( 'Search: %s', 'jackrabbit' ),
                get_search_query()
            ),
            'url'   => '',
        );
    } elseif ( is_author() ) {
        $items[] = array(
            'label' => get_the_archive_title(),
            'url'   => '',
        );
    } elseif ( is_archive() ) {
        $items[] = array(
            'label' => get_the_archive_title(),
            'url'   => '',
        );
    } elseif ( is_404() ) {
        $items[] = array(
            'label' => __( 'Not Found', 'jackrabbit' ),
            'url'   => '',
        );
    }

    if ( count( $items ) < 2 ) {
        return;
    }
    ?>
    <nav class="jk-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'jackrabbit' ); ?>">
        <ol class="jk-breadcrumbs__list">
            <?php foreach ( $items as $index => $item ) : ?>
                <?php $is_last = ( $index === array_key_last( $items ) ); ?>
                <li class="jk-breadcrumbs__item">
                    <?php if ( ! $is_last && ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>">
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    <?php else : ?>
                        <span aria-current="page">
                            <?php echo esc_html( $item['label'] ); ?>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
}

function jackrabbit_image_loading_behavior( $attributes ) {
    if ( '1' !== jackrabbit_get_option( 'lazy_load_images', '1' ) ) {
        if ( isset( $attributes['loading'] ) && 'lazy' === $attributes['loading'] ) {
            $attributes['loading'] = 'eager';
        }
        return $attributes;
    }

    if ( empty( $attributes['loading'] ) ) {
        $attributes['loading'] = 'lazy';
    }

    return $attributes;
}
add_filter( 'wp_get_attachment_image_attributes', 'jackrabbit_image_loading_behavior', 10, 1 );

/*--------------------------------------------------------------
 * Custom Header / Footer Code Injection
 *--------------------------------------------------------------*/
function jackrabbit_custom_header_code() {
    $code = jackrabbit_get_option( 'custom_header_code', '' );
    if ( ! empty( $code ) ) {
        echo $code . "\n";
    }
}
add_action( 'wp_head', 'jackrabbit_custom_header_code', 99 );

function jackrabbit_custom_footer_code() {
    $code = jackrabbit_get_option( 'custom_footer_code', '' );
    if ( ! empty( $code ) ) {
        echo $code . "\n";
    }
}
add_action( 'wp_footer', 'jackrabbit_custom_footer_code', 99 );
