<?php
namespace DanCode\ATCNotifications\Admin;

use DanCode\ATCNotifications\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}
/**
 * Handle admin functionality of plugin
 *
 * @since 1.0.0
 */
class Admin {

	/**
	* @var array Holds the settings for the frontend.
	*/
	private $settings;

	/**
	* Constructor for the Admin class.
	*
	* This method is responsible for initializing the class.
	*/
	public function __construct() {

		// Set settings
		$this->settings = Utils::settings();

		// Register hooks
		$this->hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function hooks(): void {
		\add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		\add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add admin menu
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function admin_menu(): void {

		// Use this hook to change the capability
		$capability = \apply_filters( 'atc_settings_page_capability', 'manage_options' );

		// Add notifications menu page
		\add_menu_page(
			__( 'ATC Notifications', 'atc-notifications' ),
			__( 'ATC Notifications', 'atc-notifications' ),
			$capability,
			'atc-notifications',
			array( $this, 'settings_page' ),
			'dashicons-admin-generic',
		);
	}

	/**
	 * Display admin page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function settings_page(): void {
		// Get settings
		$settings = $this->settings;

		// Include admin page html.
		include_once \ATCN_DIR . 'templates/html-admin-page-atc-notifications.php';
	}

	/**
	 * Register settings for the plugin
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_settings(): void {
		\register_setting( 'atc-notifications-settings', 'atc_notifications_settings', array( $this, 'validate_options' ) );
	}

	/**
	 * Validate options
	 *
	 * @param array $input The input from the settings form.
	 *
	 * @return array The validated options.
	 * @since 1.0.0
	 */
	public function validate_options( $input ): array {
		// Sanitize layout
		if ( isset( $input['layout'] ) ) {
			$input['layout'] = sanitize_text_field( $input['layout'] );
		}

		// Sanitize position
		if ( isset( $input['position'] ) ) {
			$input['position'] = sanitize_text_field( $input['position'] );
		}

		// Sanitize close_after
		if ( isset( $input['close_after'] ) ) {
			$input['close_after'] = absint( $input['close_after'] );
		}

		// Sanitize display_conditions
		if ( isset( $input['display_conditions'] ) ) {
			$input['display_conditions'] = array_map( 'sanitize_text_field', $input['display_conditions'] );
		} else {
			$input['display_conditions'] = array();
		}

		return $input;
	}
}
