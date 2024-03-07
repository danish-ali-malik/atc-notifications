<?php
/**
 * Plugin Name: Add to cart notifications
 * Plugin URI: https://wordpress.org/plugins/atc-notifications/
 * Description: Display add to cart notifications when a product is added to cart.
 * Author: Danish Ali Malik
 * Version: 1.0.0
 * Author URI: https://profiles.wordpress.org/danish-ali/
 * Text Domain: atc-notifications
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}

// Check if WooCommerce plugin is installed
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	// Define Constants
	if ( ! defined( 'ATCN_VERSION' ) ) {
		define( 'ATCN_VERSION', '1.0.0' );
	}

	if ( ! defined( 'ATCN_DIR' ) ) {
		define( 'ATCN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
	}

	if ( ! defined( 'ATCN_FILE' ) ) {
		define( 'ATCN_FILE', __FILE__ );
	}

	if ( ! defined( 'ATCN_PLUGIN_BASE' ) ) {
		define( 'ATCN_PLUGIN_BASE', plugin_basename( ATCN_FILE ) );
	}

	if ( ! defined( 'ATCN_URL' ) ) {
		define( 'ATCN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	include_once ATCN_DIR . 'includes/Init.php';
	// Initialize the plugin
	DanCode\ATCNotifications\Init::instance();
}
