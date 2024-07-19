<?php
/**
 * Plugin Name: WooEats
 * Description: WooEats plugin to manage online pickup and delivery with custom addons.
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

define('WOOEATS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOOEATS_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once WOOEATS_PLUGIN_DIR . 'includes/class-wooeats-admin.php';
require_once WOOEATS_PLUGIN_DIR . 'includes/class-wooeats-checkout.php';
require_once WOOEATS_PLUGIN_DIR . 'includes/class-wooeats-product.php';
require_once WOOEATS_PLUGIN_DIR . 'includes/class-wooeats-settings.php';
require_once WOOEATS_PLUGIN_DIR . 'includes/class-wooeats-order.php';

function wooeats_init() {
    new WOOEATS_Admin();
    new WOOEATS_Checkout();
    new WOOEATS_Product();
    new WOOEATS_Settings();
    new WOOEATS_Order();
}
add_action('plugins_loaded', 'wooeats_init');
