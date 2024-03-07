<?php
/**
 * Display the admin page for the plugin.
 *
 * @package ATCNotifications
 * @since  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Settings', 'atc-notifications' ); ?></h1>
	<form method="post" action="options.php">
		<?php
		// Handle nonces automatically
		settings_fields( 'atc-notifications-settings' );
		do_settings_sections( 'atc-notifications-settings' );
		do_action( 'atc_before_form', $settings );
		// Display settings saved successfull message.
		if ( isset( $_GET['settings-updated'] ) ) {
			?>
			<div id="message" class="updated notice is-dismissible">
				<p><?php esc_html_e( 'Settings saved successfully.', 'atc-notifications' ); ?></p>
			</div>
			<?php
		}
		?>
		<table class="form-table">
			<?php do_action( 'atc_before_form_fields' ); ?>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Layout', 'atc-notifications' ); ?></th>
				<td>
					<label>
						<input type="radio" name="atc_notifications_settings[layout]" value="content-left" <?php checked( 'content-left' === ( isset( $settings['layout'] ) ? $settings['layout'] : '' ) ); ?>>
						<?php esc_html_e( 'Product image within the content on the left side', 'atc-notifications' ); ?>
					</label>
					<br>
					<label>
						<input type="radio" name="atc_notifications_settings[layout]" value="background" <?php checked( 'background' === ( isset( $settings['layout'] ) ? $settings['layout'] : '' ) ); ?>>
						<?php esc_html_e( 'Product image as a background', 'atc-notifications' ); ?>
					</label>
					<?php do_action( 'atc_after_layout_fields', $settings ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Display Position', 'atc-notifications' ); ?></th>
				<td>
					<label>
						<input type="radio" name="atc_notifications_settings[position]" value="top" <?php checked( 'top' === ( isset( $settings['position'] ) ? $settings['position'] : '' ) ); ?>>
						<?php esc_html_e( 'Top', 'atc-notifications' ); ?>
					</label>
					<br>
					<label>
						<input type="radio" name="atc_notifications_settings[position]" value="bottom" <?php checked( 'bottom' === ( isset( $settings['position'] ) ? $settings['position'] : '' ) ); ?>>
						<?php esc_html_e( 'Bottom', 'atc-notifications' ); ?>
					</label>
					<?php do_action( 'atc_after_position_fields', $settings ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Close After Seconds', 'atc-notifications' ); ?></th>
				<td>
					<input type="number" name="atc_notifications_settings[close_after]" value="<?php echo isset( $settings['close_after'] ) ? esc_attr( $settings['close_after'] ) : '3'; ?>" min="1">
					<p class="description"><?php esc_html_e( 'Enter the time in seconds after which the notification should automatically close.', 'atc-notifications' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Display Conditions', 'atc-notifications' ); ?></th>
				<td>
					<select name="atc_notifications_settings[display_conditions][]" multiple>
						<option value="all_pages" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'all_pages', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'All Pages', 'atc-notifications' ); ?></option>
						<option value="shop_archive" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'shop_archive', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'Shop Archive', 'atc-notifications' ); ?></option>
						<option value="shop_archive_categories" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'shop_archive_categories', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'Shop Archive Categories', 'atc-notifications' ); ?></option>
						<option value="shop_archive_tags" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'shop_archive_tags', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'Shop Archive Tags', 'atc-notifications' ); ?></option>
						<option value="shop_archive_product_attributes" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'shop_archive_product_attributes', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'Shop Archive Product Attributes', 'atc-notifications' ); ?></option>
						<option value="single_products" 
						<?php
						if ( isset( $settings['display_conditions'] ) && in_array( 'single_products', $settings['display_conditions'], true ) ) {
							echo 'selected="selected"';}
						?>
						><?php esc_html_e( 'Single Products', 'atc-notifications' ); ?></option>
						<?php do_action( 'atc_after_display_conditions_fields', $settings ); ?>
					</select>
				</td>
			</tr>
			<?php do_action( 'atc_after_form_fields', $settings ); ?>
		</table>
		<?php submit_button(); ?>
	</form>
	<?php do_action( 'atc_after_form', $settings ); ?>
</div>
