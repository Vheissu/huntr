<?php

$labels = array(
    'name'                       => _x( 'Topics', 'Taxonomy General Name', 'huntr' ),
    'singular_name'              => _x( 'Topic', 'Taxonomy Singular Name', 'huntr' ),
    'menu_name'                  => __( 'Topics', 'huntr' ),
    'all_items'                  => __( 'All Topics', 'huntr' ),
    'parent_item'                => __( 'Parent Topic', 'huntr' ),
    'parent_item_colon'          => __( 'Parent Topic:', 'huntr' ),
    'new_item_name'              => __( 'New Topic Name', 'huntr' ),
    'add_new_item'               => __( 'Add New Topic', 'huntr' ),
    'edit_item'                  => __( 'Edit Topic', 'huntr' ),
    'update_item'                => __( 'Update Topic', 'huntr' ),
    'view_item'                  => __( 'View Topic', 'huntr' ),
    'separate_items_with_commas' => __( 'Separate topics with commas', 'huntr' ),
    'add_or_remove_items'        => __( 'Add or remove topics', 'huntr' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'huntr' ),
    'popular_items'              => __( 'Popular Topics', 'huntr' ),
    'search_items'               => __( 'Search Topics', 'huntr' ),
    'not_found'                  => __( 'Not Found', 'huntr' ),
    'no_terms'                   => __( 'No topics', 'huntr' ),
    'items_list'                 => __( 'Topics list', 'huntr' ),
    'items_list_navigation'      => __( 'Topics list navigation', 'huntr' ),
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'show_in_rest'               => true,
);
register_taxonomy( 'topics', array( 'products' ), $args );

$labels = array(
    'name'                       => _x( 'Collections', 'Taxonomy General Name', 'huntr' ),
    'singular_name'              => _x( 'Collection', 'Taxonomy Singular Name', 'huntr' ),
    'menu_name'                  => __( 'Collections', 'huntr' ),
    'all_items'                  => __( 'All Collections', 'huntr' ),
    'parent_item'                => __( 'Parent Collection', 'huntr' ),
    'parent_item_colon'          => __( 'Parent Collection:', 'huntr' ),
    'new_item_name'              => __( 'New Collection Name', 'huntr' ),
    'add_new_item'               => __( 'Add New Collection', 'huntr' ),
    'edit_item'                  => __( 'Edit Collection', 'huntr' ),
    'update_item'                => __( 'Update Collection', 'huntr' ),
    'view_item'                  => __( 'View Collection', 'huntr' ),
    'separate_items_with_commas' => __( 'Separate collections with commas', 'huntr' ),
    'add_or_remove_items'        => __( 'Add or remove collections', 'huntr' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'huntr' ),
    'popular_items'              => __( 'Popular Collections', 'huntr' ),
    'search_items'               => __( 'Search Collections', 'huntr' ),
    'not_found'                  => __( 'Not Found', 'huntr' ),
    'no_terms'                   => __( 'No collections', 'huntr' ),
    'items_list'                 => __( 'Collection list', 'huntr' ),
    'items_list_navigation'      => __( 'Collection list navigation', 'huntr' ),
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => false,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'show_in_rest'               => true,
);
register_taxonomy( 'collections', array( 'products' ), $args );

?>