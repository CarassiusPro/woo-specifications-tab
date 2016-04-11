<?php

/*
Plugin Name: Specifications for WooCommerce
Plugin URI: http://budgetphones.com.au
Description: Add a Specifications field and tab to WooCommerce
Author: Jon Mather
Author URI: http://simplewebsiteinaday.com.au
Version: 1.0
License: GPL
*/


add_filter( 'woocommerce_product_tabs', 'bl_woo_new_product_tab' );
function bl_woo_new_product_tab( $tabs ) {
	
	$tabs['specifications'] = array(
		'title' 	=> __( 'Specifications', 'woocommerce' ),
		'priority' 	=> 20,
		'callback' 	=> 'bl_woo_specifications'
	);

	return $tabs;

}
function bl_woo_specifications() {

	global $post;

	echo '<h2>Specifications</h2>';

        echo get_post_meta( $post->ID, 'bl_specification', true );

}

class bl_woo_specifications {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'specifications',
			__( 'Specifications', 'bl-languages' ),
			array( $this, 'render_metabox' ),
			'product',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Retrieve an existing value from the database.
		$bl_specification = get_post_meta( $post->ID, 'bl_specification', true );

		// Set default values.
		if( empty( $bl_specification ) ) $bl_specification = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="bl_specification" class="bl_specification_label">' . __( 'Specification', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		wp_editor( $bl_specification, 'bl_specification', array( 'media_buttons' => true ) );
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Sanitize user input.
		$bl_new_specification = isset( $_POST[ 'bl_specification' ] ) ? wp_kses_post( $_POST[ 'bl_specification' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'bl_specification', $bl_new_specification );

	}

}

new bl_woo_specifications;