<?php

function get_random_product_id() {
    $args = array( 
        'post_type' => 'products', 
        'posts_per_page' => 1,
        'numberposts' => 1,
        'orderby' => 'rand'
    );

    $product = get_posts($args);

    if ( !is_wp_error($product) && !empty($product) ) {
        $product = $product[0];

        return $product->ID;
    }

    return null;
}
