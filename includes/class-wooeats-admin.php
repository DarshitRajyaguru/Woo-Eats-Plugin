<?php
if (!defined('ABSPATH')) exit;

class WOOEATS_Admin {
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function enqueue_admin_assets() {
        wp_enqueue_style('wooeats-admin-style', WOOEATS_PLUGIN_URL . 'assets/css/admin.css');
        wp_enqueue_script('wooeats-admin-script', WOOEATS_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), null, true);
    }

    public function add_admin_menu() {
        add_menu_page(
            'WooEats Settings',
            'WooEats',
            'manage_options',
            'wooeats',
            array($this, 'settings_page'),
            'dashicons-carrot'
        );
        add_submenu_page(
            'wooeats',
            'WooEats Orders',
            'Orders',
            'manage_options',
            'wooeats-orders',
            array($this, 'orders_page')
        );
    }

    public function settings_page() {
        include(WOOEATS_PLUGIN_DIR . 'templates/admin/settings-page.php');
    }

    public function orders_page() {
        include(WOOEATS_PLUGIN_DIR . 'templates/admin/orders-page.php');
    }
}
