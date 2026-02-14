<?php
/**
 * Jackrabbit Theme Panel — Admin Settings Page
 *
 * Registers a settings page under Appearance → Jackrabbit Settings.
 * Handles option registration, sanitization, and admin asset enqueue.
 *
 * @package Jackrabbit
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

class Jackrabbit_Theme_Panel
{

    /**
     * Option key stored in wp_options.
     */
    const OPTION_KEY = 'jackrabbit_options';

    /**
     * Default values for all options.
     */
    private $defaults = array(
        // General
        'accent_color' => '#2563eb',
        'layout_width' => 740,
        'logo_upload' => '',

        // Typography
        'font_family' => 'inter',
        'base_font_size' => 18,
        'heading_scale' => '1.25',

        // Performance
        'remove_emoji' => '1',
        'remove_jquery_migrate' => '1',
        'lazy_load_images' => '1',
        'cleanup_head' => '1',

        // SEO
        'enable_jsonld' => '1',
        'enable_og_tags' => '1',
        'meta_description' => '',

        // Footer
        'footer_text' => '',
        'show_credits' => '1',
        'social_twitter' => '',
        'social_facebook' => '',
        'social_instagram' => '',
        'social_linkedin' => '',
        'footer_cta_title' => 'Discover more stories every week',
        'footer_cta_text' => 'Fresh articles, practical guides, and thoughtful writing from our latest archive.',
        'footer_cta_button_text' => 'Explore the blog',
        'footer_cta_button_url' => '/',

        // Advanced
        'custom_header_code' => '',
        'custom_footer_code' => '',
    );

    /**
     * Constructor — hook into WordPress.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Add the settings page under Appearance.
     */
    public function add_menu_page()
    {
        add_theme_page(
            __('Jackrabbit Settings', 'jackrabbit'),
            __('Jackrabbit Settings', 'jackrabbit'),
            'manage_options',
            'jackrabbit-settings',
            array($this, 'render_page')
        );
    }

    /**
     * Register settings with sanitization callback.
     */
    public function register_settings()
    {
        register_setting(
            'jackrabbit_options_group',
            self::OPTION_KEY,
            array($this, 'sanitize_options')
        );
    }

    /**
     * Enqueue admin-only CSS and JS on our settings page.
     */
    public function enqueue_admin_assets($hook)
    {
        if ('appearance_page_jackrabbit-settings' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_media();

        wp_enqueue_style(
            'jackrabbit-admin-panel',
            JACKRABBIT_URI . '/assets/css/admin-panel.css',
            array('wp-color-picker'),
            JACKRABBIT_VERSION
        );

        wp_enqueue_script(
            'jackrabbit-admin-panel',
            JACKRABBIT_URI . '/assets/js/admin-panel.js',
            array('wp-color-picker', 'jquery'),
            JACKRABBIT_VERSION,
            true
        );
    }

    /**
     * Render the settings page.
     */
    public function render_page()
    {
        $options = wp_parse_args(get_option(self::OPTION_KEY, array()), $this->defaults);
        require_once JACKRABBIT_DIR . '/inc/theme-panel-render.php';
    }

    /**
     * Sanitize all options before saving.
     */
    public function sanitize_options($input)
    {
        $sanitized = array();

        // General
        $sanitized['accent_color'] = sanitize_hex_color($input['accent_color'] ?? '#2563eb');
        $sanitized['layout_width'] = absint($input['layout_width'] ?? 740);
        $sanitized['logo_upload'] = esc_url_raw($input['logo_upload'] ?? '');

        // Typography
        $valid_fonts = array('system', 'inter', 'roboto', 'outfit', 'lora', 'merriweather', 'source_serif');
        $sanitized['font_family'] = in_array($input['font_family'] ?? 'inter', $valid_fonts, true) ? $input['font_family'] : 'inter';
        $sanitized['base_font_size'] = absint($input['base_font_size'] ?? 18);
        $valid_scales = array('1.125', '1.2', '1.25', '1.333', '1.414', '1.5');
        $sanitized['heading_scale'] = in_array($input['heading_scale'] ?? '1.25', $valid_scales, true) ? $input['heading_scale'] : '1.25';

        // Performance (checkboxes)
        $sanitized['remove_emoji'] = isset($input['remove_emoji']) ? '1' : '0';
        $sanitized['remove_jquery_migrate'] = isset($input['remove_jquery_migrate']) ? '1' : '0';
        $sanitized['lazy_load_images'] = isset($input['lazy_load_images']) ? '1' : '0';
        $sanitized['cleanup_head'] = isset($input['cleanup_head']) ? '1' : '0';

        // SEO
        $sanitized['enable_jsonld'] = isset($input['enable_jsonld']) ? '1' : '0';
        $sanitized['enable_og_tags'] = isset($input['enable_og_tags']) ? '1' : '0';
        $sanitized['meta_description'] = sanitize_textarea_field($input['meta_description'] ?? '');

        // Footer
        $sanitized['footer_text'] = wp_kses_post($input['footer_text'] ?? '');
        $sanitized['show_credits'] = isset($input['show_credits']) ? '1' : '0';
        $sanitized['social_twitter'] = esc_url_raw($input['social_twitter'] ?? '');
        $sanitized['social_facebook'] = esc_url_raw($input['social_facebook'] ?? '');
        $sanitized['social_instagram'] = esc_url_raw($input['social_instagram'] ?? '');
        $sanitized['social_linkedin'] = esc_url_raw($input['social_linkedin'] ?? '');
        $sanitized['footer_cta_title'] = sanitize_text_field($input['footer_cta_title'] ?? '');
        $sanitized['footer_cta_text'] = sanitize_text_field($input['footer_cta_text'] ?? '');
        $sanitized['footer_cta_button_text'] = sanitize_text_field($input['footer_cta_button_text'] ?? '');
        $sanitized['footer_cta_button_url'] = esc_url_raw($input['footer_cta_button_url'] ?? '');
        if (empty($sanitized['footer_cta_button_url'])) {
            $sanitized['footer_cta_button_url'] = home_url('/');
        }

        // Advanced
        $sanitized['custom_header_code'] = $input['custom_header_code'] ?? '';
        $sanitized['custom_footer_code'] = $input['custom_footer_code'] ?? '';

        // Success notice.
        add_settings_error('jackrabbit_options', 'settings_updated', __('Settings saved successfully.', 'jackrabbit'), 'updated');

        return $sanitized;
    }

    /**
     * Get defaults (used externally when needed).
     */
    public function get_defaults()
    {
        return $this->defaults;
    }
}

// Initialize the panel.
new Jackrabbit_Theme_Panel();
