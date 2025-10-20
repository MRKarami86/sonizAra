<?php
class MyTheme_Required_Plugins {
    public static function init() {
        add_action('after_setup_theme', [__CLASS__, 'check_plugins']);
    }

    public static function check_plugins() {
        $required = [
            'woocommerce/woocommerce.php',
            'elementor/elementor.php',
        ];

        foreach ($required as $plugin) {
            if (!is_plugin_active($plugin)) {
                deactivate_theme(get_stylesheet());
                wp_die('این قالب نیاز به افزونه‌های Elementor و WooCommerce دارد.');
            }
        }
    }
}
MyTheme_Required_Plugins::init();
