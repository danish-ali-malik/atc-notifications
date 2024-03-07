<?php
/**
 * Display content left layout
 *
 * @package ATCNotifications
 * @since  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}
?>
<div class="atc-overlay"></div>
<div class="atc-bg-image" style="background-image: url({image});">
	<div class="atc-notification-container">
	<?php do_action( 'atc_before_header', $settings ); ?>
		<div class="atc-notification-header">
			<span class="atc-notification-title"><?php esc_html_e( 'Added to cart', 'atc-notifications' ); ?></span>
			<span class="atc-notification-close-btn">×</span>
		</div>
		<?php do_action( 'atc_after_header', $settings ); ?>
		<div class="atc-notification-body">
			<div class="atc-notification-product-info">
				<p class="atc-notification-product-name">{title}</p>
				<p class="atc-notification-product-price"><strong><?php esc_html_e( 'Price', 'atc-notifications' ); ?>:</strong> {price_html}</p>
			</div>
		</div>
		<?php do_action( 'atc_after_body', $settings ); ?>
		<div class="atc-notification-footer">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="atc-notification-cart-btn"><?php esc_html_e( 'View cart', 'atc-notifications' ); ?></a>
		</div>
		<?php do_action( 'atc_after_footer', $settings ); ?>
	</div>
</div>
