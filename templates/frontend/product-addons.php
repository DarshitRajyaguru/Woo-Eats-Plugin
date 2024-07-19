<div id="wooeats-product-addons">
    <h2>Additional Options</h2>
    <?php
    global $product;
    $categories = get_post_meta($product->get_id(), '_wooeats_addon_categories', true);
    if ($categories) {
        foreach ($categories as $category => $addons) {
            echo '<h3>' . esc_html($category) . '</h3>';
            foreach ($addons as $addon) {
                echo '<label><input type="checkbox" name="wooeats_addons[' . esc_attr($category) . '][]" value="' . esc_attr($addon['option']) . '" data-price="' . esc_attr($addon['price']) . '" /> ' . esc_html($addon['option']) . ' (+$' . esc_html($addon['price']) . ')</label><br>';
            }
        }
    }
    ?>
</div>
