<?php
/**
 * Notification wrapper
 *
 * @package ATCNotifications
 * @since  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}
?>
<div id="atc-notification" class="atc-notification-popup <?php echo esc_attr( $position ); ?> <?php echo esc_attr( $layout ); ?>-image"></div>
