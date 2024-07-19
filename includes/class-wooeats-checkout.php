<?php
if (!defined('ABSPATH')) exit;

class WOOEATS_Checkout {
    public function __construct() {
        add_action('woocommerce_after_order_notes', array($this, 'pickup_delivery_options'));
        add_action('woocommerce_checkout_process', array($this, 'validate_pickup_delivery'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_pickup_delivery'));
    }

    public function pickup_delivery_options($checkout) {
        echo '<div id="wooeats-pickup-delivery"><h2>' . __('Pickup/Delivery Options') . '</h2>';
        
        woocommerce_form_field('wooeats_pickup_delivery', array(
            'type' => 'select',
            'class' => array('form-row-wide'),
            'label' => __('Choose Pickup or Delivery'),
            'options' => array(
                'pickup' => __('Pickup'),
                'delivery' => __('Delivery')
            )
        ), $checkout->get_value('wooeats_pickup_delivery'));

        woocommerce_form_field('wooeats_pickup_delivery_date', array(
            'type' => 'date',
            'class' => array('form-row-wide'),
            'label' => __('Choose Date (after 5 days)'),
            'required' => true,
            'custom_attributes' => array(
                'min' => date('Y-m-d', strtotime('+5 days'))
            )
        ), $checkout->get_value('wooeats_pickup_delivery_date'));

        echo '</div>';
    }

    public function validate_pickup_delivery() {
        if (!$_POST['wooeats_pickup_delivery']) {
            wc_add_notice(__('Please choose Pickup or Delivery'), 'error');
        }
        if (!$_POST['wooeats_pickup_delivery_date']) {
            wc_add_notice(__('Please choose a date'), 'error');
        }
    }

    public function save_pickup_delivery($order_id) {
        if ($_POST['wooeats_pickup_delivery']) {
            update_post_meta($order_id, '_wooeats_pickup_delivery', sanitize_text_field($_POST['wooeats_pickup_delivery']));
        }
        if ($_POST['wooeats_pickup_delivery_date']) {
            update_post_meta($order_id, '_wooeats_pickup_delivery_date', sanitize_text_field($_POST['wooeats_pickup_delivery_date']));
        }
    }
}
