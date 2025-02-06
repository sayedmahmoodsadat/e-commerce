<?php
/**
 * Block styles.
 *
 * @package advanced-theme
 * @since 1.0.0
 */

/**
 * Register block styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function advanced_theme_register_block_styles() {

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/button',
		array(
			'name'  => 'advanced-theme-flat-button',
			'label' => __( 'Flat button', 'advanced-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/list',
		array(
			'name'  => 'advanced-theme-list-underline',
			'label' => __( 'Underlined list items', 'advanced-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/group',
		array(
			'name'  => 'advanced-theme-box-shadow',
			'label' => __( 'Box shadow', 'advanced-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/column',
		array(
			'name'  => 'advanced-theme-box-shadow',
			'label' => __( 'Box shadow', 'advanced-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/columns',
		array(
			'name'  => 'advanced-theme-box-shadow',
			'label' => __( 'Box shadow', 'advanced-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/details',
		array(
			'name'  => 'advanced-theme-plus',
			'label' => __( 'Plus & minus', 'advanced-theme' ),
		)
	);
}
add_action( 'init', 'advanced_theme_register_block_styles' );

/**
 * This is an example of how to unregister a core block style.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 * @see https://github.com/WordPress/gutenberg/pull/37580
 *
 * @since 1.0.0
 *
 * @return void
 */
function advanced_theme_unregister_block_style() {
	wp_enqueue_script(
		'advanced-theme-unregister',
		get_stylesheet_directory_uri() . '/assets/js/unregister.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		ADVANCED_THEME_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'advanced_theme_unregister_block_style' );
