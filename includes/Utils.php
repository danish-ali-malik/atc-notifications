<?php
namespace DanCode\ATCNotifications;

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}

/**
 * Utility class for the plugin.
 *
 * @since 1.0.0
 */
class Utils {

	/**
	 * Get the settings for the plugin.
	 *
	 * @return array The settings for the plugin.
	 *
	 * @since 1.0.0
	 */
	public static function settings(): array {
		$settings = get_option( 'atc_notifications_settings' );
		return apply_filters( 'atc_notifications_settings', $settings );
	}
}
