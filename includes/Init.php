<?php
namespace DanCode\ATCNotifications;

use DanCode\ATCNotifications\Admin\Admin;
use DanCode\ATCNotifications\Frontend\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}

/**
 * The core plugin class that is used to initialize the plugin.
 */
final class Init {

	/**
	 * The single instance of the class.
	 *
	 * @var Init|null
	 */
	private static $instance = null;

	/**
	 * The Admin class instance.
	 *
	 * @var Admin
	 */
	public $admin;

	/**
	 * The Frontend class instance.
	 *
	 * @var Frontend
	 */
	public $frontend;

	/**
	 * Get the singleton instance of the Init class.
	 *
	 * @return Init The single instance of the class.
	 *
	 * @since 1.0.0
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
			\add_action( 'init', array( self::$instance, 'init' ), 0 );
			\register_activation_hook( ATCN_FILE, array( self::$instance, 'activate' ) );
			\add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		return self::$instance;
	}

	/**
	 * Set up the default options for the plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function activate(): void {

		// check if the option is already set then don't set it again to preserve the settings during conflict tests
		if ( \get_option( 'atc_notifications_settings' ) ) {
			return;
		}

		// Add option to the database
		\update_option(
			'atc_notifications_settings',
			array(
				'layout'             => 'content-left',
				'position'           => 'top',
				'close_after'        => 3,
				'display_conditions' => array( 'all_pages' ),
			)
		);
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init(): void {
		require_once \ATCN_DIR . 'vendor/autoload.php';

		// Initialize the Admin class for admin area.
		if ( \is_admin() ) {
			$this->admin = new Admin();
		} else {
			// Initialize the Frontend class for frontend area.
			$this->frontend = new Frontend();
		}
	}

	/**
	 * Load plugin textdomain
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function load_textdomain(): void {
		\load_plugin_textdomain(
			'atc-notifications',
			false,
			ATCN_PLUGIN_BASE . '/languages'
		);
	}
}
