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
// require_once get_theme_file_path( 'inc/woocommerce.php' );


function custom_enqueue_woocommerce_styles() {
    // Check if we are on a WooCommerce page
    if ( is_woocommerce() ) {
        // Enqueue your custom CSS file for WooCommerce pages
        wp_enqueue_style( 'custom-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_woocommerce_styles' );

function no_code_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css'); 
}
add_action('wp_enqueue_scripts', 'no_code_theme_enqueue_styles');

function my_theme_enqueue_single_post_styles() {
    if (is_single()) {  
        wp_enqueue_style('single-post-styles', get_template_directory_uri() . '/assets/css/single-post.css');
    }
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_single_post_styles');

function enqueue_custom_login_styles() {
    wp_enqueue_style('custom-login-styles', get_template_directory_uri() . '/assets/css/custom-login-styles.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_login_styles');


function enqueue_custom_cart_styles() {
    if (function_exists('is_cart') && is_cart()) {
        wp_enqueue_style(
            'custom-woocommerce-cart',
            get_template_directory_uri() . '/assets/css/woocommerce-cart.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/woocommerce-cart.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_cart_styles');

function enqueue_custom_checkout_styles() {
    // Check if it's the checkout page
    if (function_exists('is_checkout') && is_checkout()) {
        // Enqueue the custom CSS file
        wp_enqueue_style(
            'custom-woocommerce-checkout', // Handle
            get_template_directory_uri() . '/assets/css/woocommerce-checkout.css', // Path to CSS file
            array(), // Dependencies
            filemtime(get_template_directory() . '/assets/css/woocommerce-checkout.css') // Version (based on file modification time)
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_checkout_styles');


function enqueue_custom_myaccount_styles() {
    if (function_exists('is_account_page') && is_account_page()) {
        wp_enqueue_style(
            'custom-woocommerce-myaccount',
            get_template_directory_uri() . '/assets/css/woocommerce-myaccount.css', // Path to your CSS file
            array(),
            filemtime(get_template_directory() . '/assets/css/woocommerce-myaccount.css') // Version based on file modification time
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_myaccount_styles');