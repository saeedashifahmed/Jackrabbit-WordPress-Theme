<?php
/**
 * Theme Header
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

$logo_upload = jackrabbit_get_option('logo_upload', '');
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e('Skip to content', 'jackrabbit'); ?>
    </a>

    <header id="masthead" class="site-header" role="banner">
        <div class="site-header__inner">
            <div class="site-branding">
                <?php if (has_custom_logo()): ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php elseif (!empty($logo_upload)): ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo site-logo--fallback" rel="home">
                        <img src="<?php echo esc_url($logo_upload); ?>" alt="<?php esc_attr_e('Site logo', 'jackrabbit'); ?>">
                    </a>
                <?php else: ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>

                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()):
                    ?>
                    <p class="site-description">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
            </div><!-- .site-branding -->

            <div class="site-header__navigation">
                <nav id="primary-navigation" class="main-navigation" role="navigation"
                    aria-label="<?php esc_attr_e('Primary Navigation', 'jackrabbit'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'fallback_cb' => false,
                        'depth' => 2,
                    ));
                    ?>
                </nav><!-- .main-navigation -->

                <div class="site-header__controls">
                    <button class="header-icon-button search-toggle" type="button" aria-controls="header-search-panel"
                        aria-expanded="false" aria-label="<?php esc_attr_e('Toggle search', 'jackrabbit'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>

                    <button class="header-icon-button theme-toggle" type="button" aria-pressed="false"
                        aria-label="<?php esc_attr_e('Toggle color mode', 'jackrabbit'); ?>">
                        <svg class="theme-toggle__sun" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <circle cx="12" cy="12" r="4"></circle>
                            <line x1="12" y1="2" x2="12" y2="4"></line>
                            <line x1="12" y1="20" x2="12" y2="22"></line>
                            <line x1="4.93" y1="4.93" x2="6.34" y2="6.34"></line>
                            <line x1="17.66" y1="17.66" x2="19.07" y2="19.07"></line>
                            <line x1="2" y1="12" x2="4" y2="12"></line>
                            <line x1="20" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="19.07" x2="6.34" y2="17.66"></line>
                            <line x1="17.66" y1="6.34" x2="19.07" y2="4.93"></line>
                        </svg>
                        <svg class="theme-toggle__moon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3c0 0 0 0 0 0A7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>

                    <button class="menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false"
                        aria-label="<?php esc_attr_e('Toggle menu', 'jackrabbit'); ?>">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
        </div><!-- .site-header__inner -->

        <div id="header-search-panel" class="header-search-panel" hidden>
            <div class="header-search-panel__inner">
                <p class="header-search-panel__eyebrow">
                    <?php esc_html_e('Quick Search', 'jackrabbit'); ?>
                </p>
                <?php get_search_form(); ?>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="primary" class="site-content">
