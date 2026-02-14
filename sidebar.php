<?php
/**
 * Sidebar Template
 *
 * @package Jackrabbit
 */

defined('ABSPATH') || exit;

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary"
    aria-label="<?php esc_attr_e('Sidebar', 'jackrabbit'); ?>">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside><!-- #secondary -->