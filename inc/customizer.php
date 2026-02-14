<?php
/**
 * Customizer Integration
 *
 * Mirrors key Theme Panel settings in the WP Customizer for live preview.
 *
 * @package Jackrabbit
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Register Customizer sections, settings, and controls.
 */
function jackrabbit_customize_register($wp_customize)
{

    // ─── Section: Jackrabbit Theme ───
    $wp_customize->add_section('jackrabbit_theme', array(
        'title' => __('Jackrabbit Theme', 'jackrabbit'),
        'priority' => 30,
    ));

    // Accent Color
    $wp_customize->add_setting('jackrabbit_options[accent_color]', array(
        'type' => 'option',
        'default' => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'jackrabbit_accent_color', array(
        'label' => __('Accent Color', 'jackrabbit'),
        'section' => 'jackrabbit_theme',
        'settings' => 'jackrabbit_options[accent_color]',
    )));

    // Content Width
    $wp_customize->add_setting('jackrabbit_options[layout_width]', array(
        'type' => 'option',
        'default' => 740,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('jackrabbit_layout_width', array(
        'label' => __('Content Width (px)', 'jackrabbit'),
        'section' => 'jackrabbit_theme',
        'settings' => 'jackrabbit_options[layout_width]',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 600,
            'max' => 1200,
            'step' => 10,
        ),
    ));

    // Font Family
    $wp_customize->add_setting('jackrabbit_options[font_family]', array(
        'type' => 'option',
        'default' => 'inter',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('jackrabbit_font_family', array(
        'label' => __('Font Family', 'jackrabbit'),
        'section' => 'jackrabbit_theme',
        'settings' => 'jackrabbit_options[font_family]',
        'type' => 'select',
        'choices' => array(
            'system' => __('System Default', 'jackrabbit'),
            'inter' => 'Inter',
            'roboto' => 'Roboto',
            'outfit' => 'Outfit',
            'lora' => 'Lora',
            'merriweather' => 'Merriweather',
            'source_serif' => 'Source Serif 4',
        ),
    ));

    // Base Font Size
    $wp_customize->add_setting('jackrabbit_options[base_font_size]', array(
        'type' => 'option',
        'default' => 18,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('jackrabbit_base_font_size', array(
        'label' => __('Base Font Size (px)', 'jackrabbit'),
        'section' => 'jackrabbit_theme',
        'settings' => 'jackrabbit_options[base_font_size]',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 14,
            'max' => 24,
            'step' => 1,
        ),
    ));

    // Footer Text
    $wp_customize->add_setting('jackrabbit_options[footer_text]', array(
        'type' => 'option',
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('jackrabbit_footer_text', array(
        'label' => __('Footer Text', 'jackrabbit'),
        'section' => 'jackrabbit_theme',
        'settings' => 'jackrabbit_options[footer_text]',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'jackrabbit_customize_register');
