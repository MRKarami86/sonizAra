<?php
class MyTheme_Required_Plugins {

    public static function init() {
        add_action('after_setup_theme', [__CLASS__, 'check_required_plugins']);
    }

    public static function check_required_plugins() {
        // فقط در بخش مدیریت بررسی شود
        if (!is_admin()) {
            return;
        }

        // لود فایل functions پلاگین‌ها
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // لیست پلاگین‌های مورد نیاز
        $required_plugins = [
            [
                'name' => 'Elementor Page Builder',
                'slug' => 'elementor',
                'file' => 'elementor/elementor.php',
                'url'  => 'https://wordpress.org/plugins/elementor/'
            ],
            [
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'file' => 'woocommerce/woocommerce.php',
                'url'  => 'https://wordpress.org/plugins/woocommerce/'
            ],
            [
                'name' => 'Contact Form 7',
                'slug' => 'contact-form-7',
                'file' => 'contact-form-7/wp-contact-form-7.php',
                'url'  => 'https://wordpress.org/plugins/contact-form-7/',
                'required' => false // اختیاری
            ]
        ];

        $missing_plugins = [];
        $inactive_plugins = [];

        foreach ($required_plugins as $plugin) {
            $is_required = $plugin['required'] ?? true;

            // بررسی وجود پلاگین
            if (!file_exists(WP_PLUGIN_DIR . '/' . $plugin['file'])) {
                if ($is_required) {
                    $missing_plugins[] = $plugin;
                }
            }
            // بررسی فعال بودن پلاگین
            elseif (!is_plugin_active($plugin['file'])) {
                if ($is_required) {
                    $inactive_plugins[] = $plugin;
                }
            }
        }

        // نمایش پیغام‌های خطا
        if (!empty($missing_plugins) || !empty($inactive_plugins)) {
            add_action('admin_notices', function() use ($missing_plugins, $inactive_plugins) {
                ?>
                <div class="notice notice-error is-dismissible">
                    <h3>📋 نیازمندی‌های قالب SonizAra</h3>
                    <p>برای عملکرد کامل قالب، لطفاً افزونه‌های زیر را بررسی کنید:</p>

                    <?php if (!empty($missing_plugins)): ?>
                        <div style="margin: 10px 0; padding: 10px; background: #ffeaa7; border-right: 4px solid #fdcb6e;">
                            <h4 style="margin-top: 0;">❌ افزونه‌های نصب نشده:</h4>
                            <ul style="margin-bottom: 0;">
                                <?php foreach ($missing_plugins as $plugin): ?>
                                    <li>
                                        <strong><?php echo esc_html($plugin['name']); ?></strong>
                                        -
                                        <a href="<?php echo admin_url('plugin-install.php?s=' . $plugin['slug'] . '&tab=search&type=term'); ?>" target="_blank">
                                            نصب افزونه
                                        </a>
                                        |
                                        <a href="<?php echo esc_url($plugin['url']); ?>" target="_blank">
                                            اطلاعات بیشتر
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($inactive_plugins)): ?>
                        <div style="margin: 10px 0; padding: 10px; background: #fab1a0; border-right: 4px solid #e17055;">
                            <h4 style="margin-top: 0;">⚠️ افزونه‌های غیرفعال:</h4>
                            <ul style="margin-bottom: 0;">
                                <?php foreach ($inactive_plugins as $plugin): ?>
                                    <li>
                                        <strong><?php echo esc_html($plugin['name']); ?></strong>
                                        -
                                        <a href="<?php echo admin_url('plugins.php'); ?>">
                                            فعال‌سازی افزونه
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <p style="margin-bottom: 0;">
                        <small>پس از نصب و فعال‌سازی افزونه‌ها، این پیغام به طور خودکار حذف می‌شود.</small>
                    </p>
                </div>
                <?php
            });
        }

        // نمایش پیغام موفقیت پس از فعال‌سازی همه پلاگین‌ها
        if (empty($missing_plugins) && empty($inactive_plugins)) {
            add_action('admin_notices', function() {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p>✅ همه نیازمندی‌های قالب SonizAra به درستی تنظیم شده‌اند.</p>
                </div>
                <?php
            });
        }
    }

    // بررسی دوره‌ای پلاگین‌ها (هر 12 ساعت)
    public static function schedule_plugin_check() {
        $last_check = get_transient('sonizara_plugins_last_check');

        if (!$last_check) {
            self::check_required_plugins();
            set_transient('sonizara_plugins_last_check', time(), 12 * HOUR_IN_SECONDS);
        }
    }
}

// راه‌اندازی سیستم
MyTheme_Required_Plugins::init();

// بررسی هنگام لود هر صفحه در پیشخوان
add_action('admin_init', ['MyTheme_Required_Plugins', 'schedule_plugin_check']);

// بررسی هنگام فعال‌سازی قالب
add_action('after_switch_theme', function() {
    delete_transient('sonizara_plugins_last_check');
    MyTheme_Required_Plugins::check_required_plugins();
});
