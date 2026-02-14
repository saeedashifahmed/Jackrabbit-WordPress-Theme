<?php
/**
 * Jackrabbit Theme Panel — Render
 *
 * HTML output for the Jackrabbit Settings page.
 *
 * @package Jackrabbit
 * @since   1.0.0
 *
 * @var array $options — Parsed theme options passed from class-jackrabbit-theme-panel.php
 */

defined('ABSPATH') || exit;
?>

<div class="wrap jackrabbit-panel">

    <div class="jackrabbit-panel__header">
        <div class="jackrabbit-panel__logo">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="36" height="36" rx="8" fill="#2563eb" />
                <path d="M10 26V14l4-4h8l4 4v12l-4 0v-6h-2v6h-4v-6h-2v6z" fill="#fff" />
            </svg>
            <div>
                <h1>
                    <?php esc_html_e('Jackrabbit Settings', 'jackrabbit'); ?>
                </h1>
                <span class="jackrabbit-panel__version">v
                    <?php echo esc_html(JACKRABBIT_VERSION); ?>
                </span>
            </div>
        </div>
        <p class="jackrabbit-panel__subtitle">
            <?php esc_html_e('Configure your theme for maximum speed, beautiful typography, and perfect SEO.', 'jackrabbit'); ?>
        </p>
    </div>

    <?php settings_errors('jackrabbit_options'); ?>

    <div class="jackrabbit-panel__body">
        <!-- Tabs Navigation -->
        <nav class="jackrabbit-tabs" role="tablist">
            <button class="jackrabbit-tab active" role="tab" aria-selected="true" data-tab="general">
                <span class="tab-icon">⚙️</span>
                <?php esc_html_e('General', 'jackrabbit'); ?>
            </button>
            <button class="jackrabbit-tab" role="tab" aria-selected="false" data-tab="typography">
                <span class="tab-icon">🔤</span>
                <?php esc_html_e('Typography', 'jackrabbit'); ?>
            </button>
            <button class="jackrabbit-tab" role="tab" aria-selected="false" data-tab="performance">
                <span class="tab-icon">⚡</span>
                <?php esc_html_e('Performance', 'jackrabbit'); ?>
            </button>
            <button class="jackrabbit-tab" role="tab" aria-selected="false" data-tab="seo">
                <span class="tab-icon">🔍</span>
                <?php esc_html_e('SEO', 'jackrabbit'); ?>
            </button>
            <button class="jackrabbit-tab" role="tab" aria-selected="false" data-tab="footer">
                <span class="tab-icon">📋</span>
                <?php esc_html_e('Footer', 'jackrabbit'); ?>
            </button>
            <button class="jackrabbit-tab" role="tab" aria-selected="false" data-tab="advanced">
                <span class="tab-icon">🛠️</span>
                <?php esc_html_e('Advanced', 'jackrabbit'); ?>
            </button>
        </nav>

        <!-- Settings Form -->
        <form method="post" action="options.php" class="jackrabbit-form">
            <?php settings_fields('jackrabbit_options_group'); ?>

            <!-- ======================== GENERAL TAB ======================== -->
            <div class="jackrabbit-tab-content active" id="tab-general" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('General Settings', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Customize the overall look of your theme.', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field">
                        <label for="jk-accent-color">
                            <?php esc_html_e('Accent Color', 'jackrabbit'); ?>
                        </label>
                        <input type="text" id="jk-accent-color" name="jackrabbit_options[accent_color]"
                            value="<?php echo esc_attr($options['accent_color']); ?>" class="jk-color-picker"
                            data-default-color="#2563eb">
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Used for links, buttons, and interactive elements.', 'jackrabbit'); ?>
                        </p>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-layout-width">
                            <?php esc_html_e('Content Width (px)', 'jackrabbit'); ?>
                        </label>
                        <input type="number" id="jk-layout-width" name="jackrabbit_options[layout_width]"
                            value="<?php echo esc_attr($options['layout_width']); ?>" min="600" max="1200" step="10">
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Maximum width of the content area. 680–800px is ideal for readability.', 'jackrabbit'); ?>
                        </p>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-logo-upload">
                            <?php esc_html_e('Logo URL', 'jackrabbit'); ?>
                        </label>
                        <div class="jackrabbit-media-field">
                            <input type="url" id="jk-logo-upload" name="jackrabbit_options[logo_upload]"
                                value="<?php echo esc_attr($options['logo_upload']); ?>" placeholder="https://">
                            <button type="button" class="button jk-upload-btn">
                                <?php esc_html_e('Upload', 'jackrabbit'); ?>
                            </button>
                        </div>
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Upload or enter a URL for your site logo. You can also use Appearance → Customize → Site Identity.', 'jackrabbit'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ======================== TYPOGRAPHY TAB ======================== -->
            <div class="jackrabbit-tab-content" id="tab-typography" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('Typography', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Fine-tune the reading experience with the perfect font and size.', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field">
                        <label for="jk-font-family">
                            <?php esc_html_e('Font Family', 'jackrabbit'); ?>
                        </label>
                        <select id="jk-font-family" name="jackrabbit_options[font_family]">
                            <option value="system" <?php selected($options['font_family'], 'system'); ?>>
                                <?php esc_html_e('System Default', 'jackrabbit'); ?>
                            </option>
                            <optgroup label="<?php esc_attr_e('Sans-Serif (Google Fonts)', 'jackrabbit'); ?>">
                                <option value="inter" <?php selected($options['font_family'], 'inter'); ?>>Inter
                                </option>
                                <option value="roboto" <?php selected($options['font_family'], 'roboto'); ?>>Roboto
                                </option>
                                <option value="outfit" <?php selected($options['font_family'], 'outfit'); ?>>Outfit
                                </option>
                            </optgroup>
                            <optgroup label="<?php esc_attr_e('Serif (Google Fonts)', 'jackrabbit'); ?>">
                                <option value="lora" <?php selected($options['font_family'], 'lora'); ?>>Lora</option>
                                <option value="merriweather" <?php selected($options['font_family'], 'merriweather'); ?>>Merriweather</option>
                                <option value="source_serif" <?php selected($options['font_family'], 'source_serif'); ?>>Source Serif 4</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-base-font-size">
                            <?php esc_html_e('Base Font Size (px)', 'jackrabbit'); ?>
                        </label>
                        <input type="number" id="jk-base-font-size" name="jackrabbit_options[base_font_size]"
                            value="<?php echo esc_attr($options['base_font_size']); ?>" min="14" max="24" step="1">
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('16–20px is recommended for optimal readability.', 'jackrabbit'); ?>
                        </p>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-heading-scale">
                            <?php esc_html_e('Heading Scale Ratio', 'jackrabbit'); ?>
                        </label>
                        <select id="jk-heading-scale" name="jackrabbit_options[heading_scale]">
                            <option value="1.125" <?php selected($options['heading_scale'], '1.125'); ?>>
                                <?php esc_html_e('1.125 — Minor Second (compact)', 'jackrabbit'); ?>
                            </option>
                            <option value="1.2" <?php selected($options['heading_scale'], '1.2'); ?>>
                                <?php esc_html_e('1.200 — Minor Third', 'jackrabbit'); ?>
                            </option>
                            <option value="1.25" <?php selected($options['heading_scale'], '1.25'); ?>>
                                <?php esc_html_e('1.250 — Major Third (recommended)', 'jackrabbit'); ?>
                            </option>
                            <option value="1.333" <?php selected($options['heading_scale'], '1.333'); ?>>
                                <?php esc_html_e('1.333 — Perfect Fourth', 'jackrabbit'); ?>
                            </option>
                            <option value="1.414" <?php selected($options['heading_scale'], '1.414'); ?>>
                                <?php esc_html_e('1.414 — Augmented Fourth', 'jackrabbit'); ?>
                            </option>
                            <option value="1.5" <?php selected($options['heading_scale'], '1.5'); ?>>
                                <?php esc_html_e('1.500 — Perfect Fifth (bold)', 'jackrabbit'); ?>
                            </option>
                        </select>
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Controls how much larger each heading level is relative to the previous one.', 'jackrabbit'); ?>
                        </p>
                    </div>

                    <!-- Typography Preview -->
                    <div class="jackrabbit-preview" id="jk-type-preview">
                        <h3>
                            <?php esc_html_e('Preview', 'jackrabbit'); ?>
                        </h3>
                        <div class="jk-type-preview-content">
                            <p style="font-size: 2em; font-weight: 700; margin: 0 0 .25em;">Heading One</p>
                            <p style="font-size: 1.5em; font-weight: 600; margin: 0 0 .25em;">Heading Two</p>
                            <p style="margin: 0;">The quick brown fox jumps over the lazy dog. Good typography makes the
                                act of reading effortless.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== PERFORMANCE TAB ======================== -->
            <div class="jackrabbit-tab-content" id="tab-performance" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('Performance', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Strip out bloat and maximize Core Web Vitals scores.', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[remove_emoji]" value="1" <?php checked($options['remove_emoji'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Remove Emoji Scripts', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Removes WordPress emoji JavaScript and CSS. Saves ~46KB.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[remove_jquery_migrate]" value="1" <?php checked($options['remove_jquery_migrate'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Remove jQuery Migrate', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Dequeues jquery-migrate on the front-end. May break legacy plugins.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[lazy_load_images]" value="1" <?php checked($options['lazy_load_images'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Lazy Load Images', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Adds native lazy loading to images via JavaScript observer for offscreen images.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[cleanup_head]" value="1" <?php checked($options['cleanup_head'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Clean Up &lt;head&gt;', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Removes RSD links, WLW manifest, shortlinks, REST API links, and WP version from head.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== SEO TAB ======================== -->
            <div class="jackrabbit-tab-content" id="tab-seo" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('SEO', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Built-in SEO essentials. Works alongside plugins like Yoast or Rank Math (they will take priority if active).', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[enable_jsonld]" value="1" <?php checked($options['enable_jsonld'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('JSON-LD Structured Data', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Outputs WebSite and Article schema for rich results in Google Search.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[enable_og_tags]" value="1" <?php checked($options['enable_og_tags'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Open Graph & Twitter Cards', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Adds og: and twitter: meta tags for beautiful social media previews.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-meta-desc">
                            <?php esc_html_e('Default Meta Description', 'jackrabbit'); ?>
                        </label>
                        <textarea id="jk-meta-desc" name="jackrabbit_options[meta_description]" rows="3" maxlength="160"
                            placeholder="<?php esc_attr_e('A brief description of your site for search engines…', 'jackrabbit'); ?>"><?php echo esc_textarea($options['meta_description']); ?></textarea>
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Used on the homepage and as fallback. Max 160 characters recommended.', 'jackrabbit'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ======================== FOOTER TAB ======================== -->
            <div class="jackrabbit-tab-content" id="tab-footer" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('Footer', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Customize your site footer and social links.', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field">
                        <label for="jk-footer-text">
                            <?php esc_html_e('Footer Text', 'jackrabbit'); ?>
                        </label>
                        <textarea id="jk-footer-text" name="jackrabbit_options[footer_text]" rows="2"
                            placeholder="<?php esc_attr_e('Custom footer message…', 'jackrabbit'); ?>"><?php echo esc_textarea($options['footer_text']); ?></textarea>
                    </div>

                    <div class="jackrabbit-field jackrabbit-field--toggle">
                        <label class="jackrabbit-toggle">
                            <input type="checkbox" name="jackrabbit_options[show_credits]" value="1" <?php checked($options['show_credits'], '1'); ?>>
                            <span class="jackrabbit-toggle__slider"></span>
                        </label>
                        <div class="jackrabbit-toggle__label">
                            <strong>
                                <?php esc_html_e('Show Theme Credits', 'jackrabbit'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Display "Powered by Jackrabbit" in the footer.', 'jackrabbit'); ?>
                            </p>
                        </div>
                    </div>

                    <hr class="jackrabbit-divider">
                    <h3 class="jackrabbit-card__subtitle">
                        <?php esc_html_e('Social Media Links', 'jackrabbit'); ?>
                    </h3>

                    <div class="jackrabbit-field">
                        <label for="jk-social-twitter">
                            <?php esc_html_e('X (Twitter) URL', 'jackrabbit'); ?>
                        </label>
                        <input type="url" id="jk-social-twitter" name="jackrabbit_options[social_twitter]"
                            value="<?php echo esc_attr($options['social_twitter']); ?>"
                            placeholder="https://x.com/yourhandle">
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-social-facebook">
                            <?php esc_html_e('Facebook URL', 'jackrabbit'); ?>
                        </label>
                        <input type="url" id="jk-social-facebook" name="jackrabbit_options[social_facebook]"
                            value="<?php echo esc_attr($options['social_facebook']); ?>"
                            placeholder="https://facebook.com/yourpage">
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-social-instagram">
                            <?php esc_html_e('Instagram URL', 'jackrabbit'); ?>
                        </label>
                        <input type="url" id="jk-social-instagram" name="jackrabbit_options[social_instagram]"
                            value="<?php echo esc_attr($options['social_instagram']); ?>"
                            placeholder="https://instagram.com/yourhandle">
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-social-linkedin">
                            <?php esc_html_e('LinkedIn URL', 'jackrabbit'); ?>
                        </label>
                        <input type="url" id="jk-social-linkedin" name="jackrabbit_options[social_linkedin]"
                            value="<?php echo esc_attr($options['social_linkedin']); ?>"
                            placeholder="https://linkedin.com/company/yourcompany">
                    </div>

                    <hr class="jackrabbit-divider">
                    <h3 class="jackrabbit-card__subtitle">
                        <?php esc_html_e('Footer Call-to-Action', 'jackrabbit'); ?>
                    </h3>

                    <div class="jackrabbit-field">
                        <label for="jk-footer-cta-title">
                            <?php esc_html_e('CTA Title', 'jackrabbit'); ?>
                        </label>
                        <input type="text" id="jk-footer-cta-title" name="jackrabbit_options[footer_cta_title]"
                            value="<?php echo esc_attr($options['footer_cta_title']); ?>"
                            placeholder="<?php esc_attr_e('Discover more stories every week', 'jackrabbit'); ?>">
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-footer-cta-text">
                            <?php esc_html_e('CTA Description', 'jackrabbit'); ?>
                        </label>
                        <textarea id="jk-footer-cta-text" name="jackrabbit_options[footer_cta_text]" rows="2"
                            placeholder="<?php esc_attr_e('Fresh articles, practical guides, and thoughtful writing from our latest archive.', 'jackrabbit'); ?>"><?php echo esc_textarea($options['footer_cta_text']); ?></textarea>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-footer-cta-button-text">
                            <?php esc_html_e('CTA Button Label', 'jackrabbit'); ?>
                        </label>
                        <input type="text" id="jk-footer-cta-button-text"
                            name="jackrabbit_options[footer_cta_button_text]"
                            value="<?php echo esc_attr($options['footer_cta_button_text']); ?>"
                            placeholder="<?php esc_attr_e('Explore the blog', 'jackrabbit'); ?>">
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-footer-cta-button-url">
                            <?php esc_html_e('CTA Button URL', 'jackrabbit'); ?>
                        </label>
                        <input type="url" id="jk-footer-cta-button-url" name="jackrabbit_options[footer_cta_button_url]"
                            value="<?php echo esc_attr($options['footer_cta_button_url']); ?>" placeholder="https://">
                    </div>
                </div>
            </div>

            <!-- ======================== ADVANCED TAB ======================== -->
            <div class="jackrabbit-tab-content" id="tab-advanced" role="tabpanel">
                <div class="jackrabbit-card">
                    <h2 class="jackrabbit-card__title">
                        <?php esc_html_e('Advanced', 'jackrabbit'); ?>
                    </h2>
                    <p class="jackrabbit-card__desc">
                        <?php esc_html_e('Inject custom code into the header or footer of every page.', 'jackrabbit'); ?>
                    </p>

                    <div class="jackrabbit-field">
                        <label for="jk-custom-header-code">
                            <?php esc_html_e('Custom Header Code', 'jackrabbit'); ?>
                        </label>
                        <textarea id="jk-custom-header-code" name="jackrabbit_options[custom_header_code]" rows="5"
                            class="code-textarea"
                            placeholder="<?php esc_attr_e('<!-- e.g. Google Analytics, custom CSS, etc. -->', 'jackrabbit'); ?>"><?php echo esc_textarea($options['custom_header_code']); ?></textarea>
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Outputs before the closing &lt;/head&gt; tag. Use for analytics, fonts, or custom CSS.', 'jackrabbit'); ?>
                        </p>
                    </div>

                    <div class="jackrabbit-field">
                        <label for="jk-custom-footer-code">
                            <?php esc_html_e('Custom Footer Code', 'jackrabbit'); ?>
                        </label>
                        <textarea id="jk-custom-footer-code" name="jackrabbit_options[custom_footer_code]" rows="5"
                            class="code-textarea"
                            placeholder="<?php esc_attr_e('<!-- e.g. chat widgets, tracking pixels, etc. -->', 'jackrabbit'); ?>"><?php echo esc_textarea($options['custom_footer_code']); ?></textarea>
                        <p class="jackrabbit-field__help">
                            <?php esc_html_e('Outputs before the closing &lt;/body&gt; tag.', 'jackrabbit'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="jackrabbit-panel__actions">
                <?php submit_button(__('Save Settings', 'jackrabbit'), 'primary large', 'submit', false); ?>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('themes.php?page=jackrabbit-settings&reset=1'), 'jackrabbit_reset')); ?>"
                    class="button button-secondary"
                    onclick="return confirm('<?php esc_attr_e('Reset all settings to defaults?', 'jackrabbit'); ?>');">
                    <?php esc_html_e('Reset to Defaults', 'jackrabbit'); ?>
                </a>
            </div>

        </form>
    </div><!-- .jackrabbit-panel__body -->

    <div class="jackrabbit-panel__footer">
        <p>
            <?php printf(esc_html__('Jackrabbit Theme v%s — Built for speed. Designed for readers.', 'jackrabbit'), esc_html(JACKRABBIT_VERSION)); ?>
        </p>
    </div>

</div><!-- .jackrabbit-panel -->
