<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package no-code-theme
 * @since 1.0.0
 */

// Hooks.
require_once get_theme_file_path( 'inc/hooks.php' );

// Woocommerce.
require_once get_theme_file_path( 'inc/woocommerce.php' );


function custom_enqueue_woocommerce_styles() {
    // Check if we are on a WooCommerce page
    if ( is_woocommerce() ) {
        // Enqueue your custom CSS file for WooCommerce pages
        wp_enqueue_style( 'custom-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_woocommerce_styles' );
