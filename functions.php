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
