<?php

function register_app_menus() {

	$locations = array(
		'main-menu' => __( 'The main site menu', 'huntr' ),
		'footer-menu' => __( 'The footer menu', 'huntr' ),
	);
	register_nav_menus( $locations );

}

add_action( 'init', 'register_app_menus' );

?>