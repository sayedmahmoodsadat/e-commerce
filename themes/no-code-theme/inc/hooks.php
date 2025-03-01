<?php

function urben_style_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'urben-style-block-editor',
		get_theme_file_uri( 'assets/js/block-editor.js' ),
		array( 
			'wp-blocks', 
			'wp-dom-ready', 
			'wp-edit-post' 
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'urben_style_enqueue_block_editor_assets' );

function urben_style_enqueue_block_assets() {
	wp_enqueue_style(
		'urben-style-block-styles',
		get_theme_file_uri( 'assets/css/block-styles.css' )
	);
}
add_action( 'enqueue_block_assets', 'urben_style_enqueue_block_assets' );
