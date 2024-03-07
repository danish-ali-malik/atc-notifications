<?php
namespace DanCode\ATCNotifications\Frontend;

use DanCode\ATCNotifications\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}
/**
 * Handle frontend functionality of plugin
 *
 * @since 1.0.0
 */
class Frontend {

	/**
	* @var array Holds the settings.
	*/
	private $settings;

	/**
	* Constructor for the Frontend class.
	*
	* @return void
	* @since 1.0.0
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
		\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		\add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'atc_fragments' ) );
		\add_action( 'wp_footer', array( $this, 'display_notification' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_scripts(): void {

		// If the notification should not be displayed then return
		if ( ! $this->should_display() ) {
			return;
		}

		// Filter the close after seconds
		$close_after = \apply_filters( 'atc_close_after', isset( $this->settings['close_after'] ) ? $this->settings['close_after'] : 3 );

		// Check the selected layout and ge the html of it accordingly
		if ( 'content-left' === $this->settings['layout'] && isset( $this->settings['layout'] ) ) {
			$layout = $this->layout_content_left();
		} else {
			$layout = $this->layout_bg_image();
		}

		// Enqueue styles and scripts
		\wp_enqueue_style( 'atc-notifications', \ATCN_URL . 'assets/css/atc-notifications.css', array(), \ATCN_VERSION );
		\wp_enqueue_script( 'atc-notifications', \ATCN_URL . 'assets/js/atc-notifications.js', array( 'jquery' ), \ATCN_VERSION, true );

		// Localize the script
		\wp_localize_script(
			'atc-notifications',
			'ATCNotifications',
			array(
				'close_after' => \esc_attr( $close_after ),
				'layout'      => \wp_kses_post( $layout ),
			)
		);
	}

	/**
	 * Add to cart fragments
	 *
	 * @param array $fragments Fragments array.
	 * @return array|null
	 * @since 1.0.0
	 */
	public function atc_fragments( $fragments ): array {

		// If the notification should not be displayed then return
		if ( ! $this->should_display() ) {
			return null;
		}

		if ( isset( $_POST['product_id'] ) && \is_numeric( $_POST['product_id'] ) ) {
			$product_id = \absint( \sanitize_text_field( $_POST['product_id'] ) );

			// Get product by id
			$product = \wc_get_product( $product_id );

			// If product exists then get the product data
			if ( $product ) {
				$product_image_url = \wp_get_attachment_image_src( $product->get_image_id(), 'full' );

				if ( isset( $product_image_url[0] ) ) {
					$product_image_url = $product_image_url[0];
				} else {
					$product_image_url = \wc_placeholder_img_src( 'full' );
				}

				// Set the product data
				$fragments['product_data'] = array(
					'name'       => \esc_html( $product->get_name() ),
					'url'        => \esc_url( $product->get_permalink() ),
					'price_html' => \wp_kses_post( $product->get_price_html() ),
					'image'      => \esc_url( $product_image_url ),
				);
			}
		}

		return $fragments;
	}

	/**
	 * Display notification
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_notification(): void {

		// If the notification should not be displayed then return
		if ( ! $this->should_display() ) {
			return;
		}

		// Get the settings
		$settings = $this->settings;

		// Get the position
		$position = isset( $settings['position'] ) ? $settings['position'] : 'top';

		// Get the layout
		$layout = isset( $settings['layout'] ) ? $settings['layout'] : 'content-left';

		$path = \locate_template( array( 'atc-notifications/templates/html-atc-notifications.php' ) );

		// Include frontend html if it doesn't exist in the theme.
		if ( $path ) {
			include_once $path;
		} else {
			include_once \ATCN_DIR . 'templates/html-atc-notifications.php';
		}
	}

	/**
	 * Display content left layout
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function layout_content_left(): string {

		// Get the settings
		$settings = $this->settings;

		$path = \locate_template( array( 'atc-notifications/templates/html-atc-notifications-content-left.php' ) );

		ob_start();

		// Include frontend html if it doesn't exist in the theme.
		if ( $path ) {
			include_once $path;
		} else {
			include_once \ATCN_DIR . 'templates/html-atc-notifications-content-left.php';
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Display background layout
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function layout_bg_image(): string {

		// Get the settings
		$settings = $this->settings;

		$path = \locate_template( array( 'atc-notifications/templates/html-atc-notifications-bg-image.php' ) );

		ob_start();
		// Include frontend html if it doesn't exist in the theme.
		if ( $path ) {
			include $path;
		} else {
			include_once \ATCN_DIR . 'templates/html-atc-notifications-bg-image.php';
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Check if the notification should be displayed
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	private function should_display(): bool {

		$display = false;

		// Get settings
		$settings = $this->settings;

		// If settings are not set then check for the filter
		if ( ! $settings || ! isset( $settings['display_conditions'] ) ) {
			return \apply_filters( 'atc_should_display_notification', $display );
		}

		// If display conditions are empty then check for the filter
		if ( isset( $settings['display_conditions'] ) && empty( $settings['display_conditions'] ) ) {
			return \apply_filters( 'atc_should_display_notification', $display );
		}

		// If all pages is selected then display the notification
		if ( \in_array( 'all_pages', $settings['display_conditions'], true ) ) {
			$display = true;
		} else {
			// If shop archive is selected then display the notification
			if ( \is_shop() && \in_array( 'shop_archive', $settings['display_conditions'], true ) ) {
				$display = true;
			}

			// If shop archive categories is selected then display the notification
			if ( \is_product_category() && \in_array( 'shop_archive_categories', $settings['display_conditions'], true ) ) {
				$display = true;
			}

			// If shop archive tags is selected then display the notification
			if ( \is_product_tag() && \in_array( 'shop_archive_tags', $settings['display_conditions'], true ) ) {
				$display = true;
			}

			// If shop archive product attributes is selected then display the notification
			if ( \is_product_taxonomy() && \in_array( 'shop_archive_product_attributes', $settings['display_conditions'], true ) ) {
				$display = true;
			}

			// If single products is selected then display the notification
			if ( \is_product() && \in_array( 'single_products', $settings['display_conditions'], true ) ) {
				$display = true;
			}
		}

		return \apply_filters( 'atc_should_display_notification', $display );
	}
}
