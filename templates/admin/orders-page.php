<div class="wrap">
    <h1>WooEats Orders</h1>
    <?php
    $args = array(
        'post_type' => 'shop_order',
        'post_status' => 'any',
        'meta_query' => array(
            array(
                'key' => '_wooeats_addons',
                'compare' => 'EXISTS'
            )
        )
    );
    $orders = new WP_Query($args);
    if ($orders->have_posts()) {
        while ($orders->have_posts()) {
            $orders->the_post();
            $order = wc_get_order(get_the_ID());
            include 'order-template.php';
        }
    } else {
        echo '<p>No orders found.</p>';
    }
    wp_reset_postdata();
    ?>
</div>
