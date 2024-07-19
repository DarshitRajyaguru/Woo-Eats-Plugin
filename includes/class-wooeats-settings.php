<?php
if (!defined('ABSPATH')) exit;

class WOOEATS_Settings {
    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings() {
        register_setting('wooeats_settings', 'wooeats_settings_options');

        add_settings_section(
            'wooeats_settings_section',
            'General Settings',
            array($this, 'settings_section_callback'),
            'wooeats'
        );

        add_settings_field(
            'wooeats_enable_service',
            'Enable Pickup/Delivery Service',
            array($this, 'enable_service_callback'),
            'wooeats',
            'wooeats_settings_section'
        );

        add_settings_field(
            'wooeats_enable_pickup',
            'Enable Pickup Service',
            array($this, 'enable_pickup_callback'),
            'wooeats',
            'wooeats_settings_section'
        );

        add_settings_field(
            'wooeats_enable_delivery',
            'Enable Delivery Service',
            array($this, 'enable_delivery_callback'),
            'wooeats',
            'wooeats_settings_section'
        );

        add_settings_field(
            'wooeats_default_service',
            'Default Service',
            array($this, 'default_service_callback'),
            'wooeats',
            'wooeats_settings_section'
        );

        add_settings_field(
            'wooeats_time_interval',
            'Pickup/Delivery Time Interval (min)',
            array($this, 'time_interval_callback'),
            'wooeats',
            'wooeats_settings_section'
        );
    }

    public function settings_section_callback() {
        echo '<p>General settings for WooEats.</p>';
    }

    public function enable_service_callback() {
        $options = get_option('wooeats_settings_options');
        ?>
        <input type="checkbox" name="wooeats_settings_options[enable_service]" <?php checked(isset($options['enable_service']), true); ?> />
        <?php
    }

    public function enable_pickup_callback() {
        $options = get_option('wooeats_settings_options');
        ?>
        <input type="checkbox" name="wooeats_settings_options[enable_pickup]" <?php checked(isset($options['enable_pickup']), true); ?> />
        <?php
    }

    public function enable_delivery_callback() {
        $options = get_option('wooeats_settings_options');
        ?>
        <input type="checkbox" name="wooeats_settings_options[enable_delivery]" <?php checked(isset($options['enable_delivery']), true); ?> />
        <?php
    }

    public function default_service_callback() {
        $options = get_option('wooeats_settings_options');
        ?>
        <select name="wooeats_settings_options[default_service]">
            <option value="pickup" <?php selected($options['default_service'], 'pickup'); ?>>Pickup</option>
            <option value="delivery" <?php selected($options['default_service'], 'delivery'); ?>>Delivery</option>
        </select>
        <?php
    }

    public function time_interval_callback() {
        $options = get_option('wooeats_settings_options');
        ?>
        <input type="number" name="wooeats_settings_options[time_interval]" value="<?php echo isset($options['time_interval']) ? esc_attr($options['time_interval']) : ''; ?>" />
        <?php
    }
}
