<?php
if (!defined('ABSPATH')) exit;

// Autoload core classes
require_once get_template_directory() . '/inc/core/class-theme-setup.php';
require_once get_template_directory() . '/inc/core/class-assets-loader.php';
require_once get_template_directory() . '/inc/core/class-required-plugins.php';

// Integrations
require_once get_template_directory() . '/inc/integrations/class-elementor-support.php';
require_once get_template_directory() . '/inc/integrations/class-woocommerce-support.php';

// Helpers
require_once get_template_directory() . '/inc/helpers/template-tags.php';
require_once get_template_directory() . '/inc/helpers/security.php';


add_action('after_setup_theme', 'mytheme_check_required_plugins');
function mytheme_check_required_plugins() {
    $required_plugins = [
        'woocommerce/woocommerce.php',
        'elementor/elementor.php'
    ];

    foreach ($required_plugins as $plugin) {
        if (!is_plugin_active($plugin)) {
            deactivate_plugins(wp_get_theme()->get('TextDomain'));
            wp_die(
                '<h2>افزونه‌های ضروری فعال نیستند!</h2><p>لطفاً المنتور و ووکامرس را فعال کنید.</p>',
                'خطا در فعال‌سازی قالب',
                ['back_link' => true]
            );
        }
    }
}


/**
 * Functions for MyTheme Child
 */

// Enqueue parent and child styles
function sonizAra_child_enqueue_styles()
{
    $parent_style = 'sonizAra-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'sonizAra-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [$parent_style],
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'sonizAra_child_enqueue_styles');
