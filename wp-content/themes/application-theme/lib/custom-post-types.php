<?php

// Register Custom Post Type
function register_app_post_types() {

	$labels = array(
		'name'                  => _x( 'Products', 'Post Type General Name', 'huntr' ),
		'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'huntr' ),
		'menu_name'             => __( 'Post Types', 'huntr' ),
		'name_admin_bar'        => __( 'Products', 'huntr' ),
		'archives'              => __( 'Product Archives', 'huntr' ),
		'attributes'            => __( 'Product Attributes', 'huntr' ),
		'parent_item_colon'     => __( 'Parent Product:', 'huntr' ),
		'all_items'             => __( 'All Products', 'huntr' ),
		'add_new_item'          => __( 'Add New Product', 'huntr' ),
		'add_new'               => __( 'Add New', 'huntr' ),
		'new_item'              => __( 'New Product', 'huntr' ),
		'edit_item'             => __( 'Edit Product', 'huntr' ),
		'update_item'           => __( 'Update Product', 'huntr' ),
		'view_item'             => __( 'View Product', 'huntr' ),
		'view_items'            => __( 'View Products', 'huntr' ),
		'search_items'          => __( 'Search Product', 'huntr' ),
		'not_found'             => __( 'Not found', 'huntr' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'huntr' ),
		'featured_image'        => __( 'Featured Image', 'huntr' ),
		'set_featured_image'    => __( 'Set featured image', 'huntr' ),
		'remove_featured_image' => __( 'Remove featured image', 'huntr' ),
		'use_featured_image'    => __( 'Use as featured image', 'huntr' ),
		'insert_into_item'      => __( 'Insert into item', 'huntr' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'huntr' ),
		'items_list'            => __( 'Items list', 'huntr' ),
		'items_list_navigation' => __( 'Items list navigation', 'huntr' ),
		'filter_items_list'     => __( 'Filter items list', 'huntr' ),
	);
	$args = array(
		'label'                 => __( 'Product', 'huntr' ),
		'description'           => __( 'One or more products that have been hunted', 'huntr' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'custom-fields' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'products', $args );

}
add_action( 'init', 'register_app_post_types', 0 );


?>