<?php
if (!defined('ABSPATH')) exit;

class WOOEATS_Order {
    public function __construct() {
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_order_addons'), 10, 1);
    }

    public function display_order_addons($order) {
        $addons = get_post_meta($order->get_id(), '_wooeats_addons', true);
        if ($addons) {
            echo '<h4>WooEats Addons</h4>';
            foreach ($addons as $category => $options) {
                echo '<strong>' . esc_html($category) . '</strong><br>';
                foreach ($options as $option) {
                    echo esc_html($option) . '<br>';
                }
            }
        }
        $pickup_delivery = get_post_meta($order->get_id(), '_wooeats_pickup_delivery', true);
        $pickup_delivery_date = get_post_meta($order->get_id(), '_wooeats_pickup_delivery_date', true);
        if ($pickup_delivery) {
            echo '<h4>Pickup/Delivery</h4>';
            echo '<strong>' . esc_html($pickup_delivery) . '</strong><br>';
            echo '<strong>Date:</strong> ' . esc_html($pickup_delivery_date) . '<br>';
        }
    }
}
