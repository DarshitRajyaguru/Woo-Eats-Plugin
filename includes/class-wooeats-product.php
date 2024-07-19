<?php
if (!defined('ABSPATH')) exit;

class WOOEATS_Product {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_addons_meta_box'));
        add_action('save_post', array($this, 'save_addons'));
        add_action('woocommerce_before_add_to_cart_button', array($this, 'display_product_addons'));
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_addons_to_cart_item_data'), 10, 2);
        add_filter('woocommerce_get_item_data', array($this, 'display_addons_in_cart'), 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'add_addons_to_order_item_meta'), 10, 4);
        add_action('woocommerce_before_calculate_totals', array($this, 'update_cart_item_price'));
    }

    public function add_addons_meta_box() {
        add_meta_box(
            'wooeats_addons',
            'WooEats Addons',
            array($this, 'render_addons_meta_box'),
            'product',
            'normal',
            'high'
        );
    }

    public function render_addons_meta_box($post) {
        $categories = get_post_meta($post->ID, '_wooeats_addon_categories', true) ?: array();
        ?>
        <div id="wooeats-addons-container">
            <div id="wooeats_addons">
                <?php foreach ($categories as $category => $addons): ?>
                    <div class="wooeats_addon_category">
                        <h4><?php echo esc_html($category); ?> <button type="button" class="remove_category">Remove Category</button></h4>
                        <?php foreach ($addons as $addon): ?>
                            <div class="wooeats_addon">
                                <input type="text" name="wooeats_addon_categories[<?php echo esc_attr($category); ?>][]" value="<?php echo esc_attr($addon['option']); ?>" placeholder="Option name" />
                                <input type="number" name="wooeats_addon_prices[<?php echo esc_attr($category); ?>][]" value="<?php echo esc_attr($addon['price']); ?>" placeholder="Price" step="0.01" />
                                <select name="wooeats_addon_choices[<?php echo esc_attr($category); ?>][]">
                                    <option value="single" <?php selected($addon['choice'], 'single'); ?>>Single</option>
                                    <option value="multiple" <?php selected($addon['choice'], 'multiple'); ?>>Multiple</option>
                                </select>
                                <button type="button" class="remove_addon">Remove Addon</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add_wooeats_category">Add Category</button>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#add_wooeats_category').click(function() {
                var categoryName = prompt("Enter category name:");
                if (categoryName) {
                    $('#wooeats_addons').append('<div class="wooeats_addon_category"><h4>' + categoryName + ' <button type="button" class="remove_category">Remove Category</button></h4><div class="wooeats_addon"><input type="text" name="wooeats_addon_categories[' + categoryName + '][]" placeholder="Option name" /><input type="number" name="wooeats_addon_prices[' + categoryName + '][]" placeholder="Price" step="0.01" /><select name="wooeats_addon_choices[' + categoryName + '][]"><option value="single">Single</option><option value="multiple">Multiple</option></select><button type="button" class="remove_addon">Remove Addon</button></div></div>');
                }
            });
            $(document).on('click', '.remove_category', function() {
                $(this).closest('.wooeats_addon_category').remove();
            });
            $(document).on('click', '.remove_addon', function() {
                $(this).closest('.wooeats_addon').remove();
            });
        });
        </script>
        <?php
    }

    public function save_addons($post_id) {
        if (isset($_POST['wooeats_addon_categories'])) {
            $categories = array();
            foreach ($_POST['wooeats_addon_categories'] as $category => $addons) {
                foreach ($addons as $key => $option) {
                    $categories[$category][] = array(
                        'option' => sanitize_text_field($option),
                        'price' => sanitize_text_field($_POST['wooeats_addon_prices'][$category][$key]),
                        'choice' => sanitize_text_field($_POST['wooeats_addon_choices'][$category][$key])
                    );
                }
            }
            update_post_meta($post_id, '_wooeats_addon_categories', $categories);
        }
    }

    public function display_product_addons() {
        global $product;
        $categories = get_post_meta($product->get_id(), '_wooeats_addon_categories', true);
        if ($categories) {
            echo '<div id="wooeats-product-addons"><h2>Additional Options</h2>';
            foreach ($categories as $category => $addons) {
                echo '<h3>' . esc_html($category) . '</h3>';
                foreach ($addons as $addon) {
                    echo '<label><input type="checkbox" name="wooeats_addons[' . esc_attr($category) . '][]" value="' . esc_attr($addon['option']) . '" data-price="' . esc_attr($addon['price']) . '" /> ' . esc_html($addon['option']) . ' (+$' . esc_html($addon['price']) . ')</label><br>';
                }
            }
            echo '</div>';
        }
    }

    public function add_addons_to_cart_item_data($cart_item_data, $product_id) {
        if (isset($_POST['wooeats_addons'])) {
            $cart_item_data['wooeats_addons'] = $_POST['wooeats_addons'];
            $cart_item_data['wooeats_addons_price'] = 0;
            foreach ($_POST['wooeats_addons'] as $category => $addons) {
                foreach ($addons as $addon) {
                    $categories = get_post_meta($product_id, '_wooeats_addon_categories', true);
                    foreach ($categories[$category] as $item) {
                        if ($item['option'] == $addon) {
                            $cart_item_data['wooeats_addons_price'] += floatval($item['price']);
                        }
                    }
                }
            }
            $cart_item_data['wooeats_addons_price'] *= count($addons);
        }
        return $cart_item_data;
    }

    public function display_addons_in_cart($item_data, $cart_item) {
        if (isset($cart_item['wooeats_addons'])) {
            foreach ($cart_item['wooeats_addons'] as $category => $addons) {
                foreach ($addons as $addon) {
                    $item_data[] = array(
                        'name' => $category,
                        'value' => $addon
                    );
                }
            }
        }
        return $item_data;
    }

    public function add_addons_to_order_item_meta($item, $cart_item_key, $values, $order) {
        if (isset($values['wooeats_addons'])) {
            $item->add_meta_data('WooEats Addons', $values['wooeats_addons']);
        }
    }

    public function update_cart_item_price($cart_object) {
        foreach ($cart_object->get_cart() as $cart_item) {
            if (isset($cart_item['wooeats_addons_price'])) {
                $cart_item['data']->set_price($cart_item['data']->get_price() + $cart_item['wooeats_addons_price']);
            }
        }
    }
}
