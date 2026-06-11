<?php
/**
 * WooCommerce Support for Neon Light HK
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ────────────────────────────────────────────────────────────────
// 1. Theme Support & Style Overrides
// ────────────────────────────────────────────────────────────────

/**
 * Remove default WooCommerce styles and add theme support
 */
function nl_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Remove default WooCommerce CSS
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}
add_action( 'after_setup_theme', 'nl_woocommerce_setup' );

/**
 * Dequeue WooCommerce block CSS on frontend (optional)
 */
function nl_dequeue_wc_block_styles() {
	wp_dequeue_style( 'wc-blocks-style' );
}
add_action( 'wp_enqueue_scripts', 'nl_dequeue_wc_block_styles', 100 );

// ────────────────────────────────────────────────────────────────
// 2. Custom Product Types
// ────────────────────────────────────────────────────────────────

/**
 * Register custom product types: Rental (bookable) and Workshop (ticket)
 */
function nl_register_custom_product_types() {

	// Rental — bookable product type
	class WC_Product_NL_Rental extends WC_Product_Simple {
		public function __construct( $product ) {
			$this->product_type = 'nl_rental';
			parent::__construct( $product );
		}

		public function get_type() {
			return 'nl_rental';
		}
	}

	// Workshop — ticket product type
	class WC_Product_NL_Workshop extends WC_Product_Simple {
		public function __construct( $product ) {
			$this->product_type = 'nl_workshop';
			parent::__construct( $product );
		}

		public function get_type() {
			return 'nl_workshop';
		}
	}
}
add_action( 'woocommerce_init', 'nl_register_custom_product_types' );

/**
 * Add custom product types to the WooCommerce product type selector
 *
 * @param array $types Product types.
 * @return array
 */
function nl_add_custom_product_types( $types ) {
	$types['nl_rental']   = __( 'Neon Rental', 'neonlighthk' );
	$types['nl_workshop'] = __( 'Workshop Ticket', 'neonlighthk' );
	return $types;
}
add_filter( 'product_type_selector', 'nl_add_custom_product_types' );

/**
 * Load custom product type class
 *
 * @param string $class_name Class name.
 * @param string $product_type Product type.
 * @param string $product_type_term Product type term.
 * @return string
 */
function nl_wc_product_class( $class_name, $product_type, $product_type_term ) {
	if ( 'nl_rental' === $product_type ) {
		return 'WC_Product_NL_Rental';
	}
	if ( 'nl_workshop' === $product_type ) {
		return 'WC_Product_NL_Workshop';
	}
	return $class_name;
}
add_filter( 'woocommerce_product_class', 'nl_wc_product_class', 10, 3 );

// ────────────────────────────────────────────────────────────────
// 3. Custom Product Tabs
// ────────────────────────────────────────────────────────────────

/**
 * Add custom product tabs for workshop/rental products
 *
 * @param array $tabs Default tabs.
 * @return array
 */
function nl_custom_product_tabs( $tabs ) {
	global $product;

	if ( ! $product ) {
		return $tabs;
	}

	$product_type = $product->get_type();

	// Workshop tab
	if ( 'nl_workshop' === $product_type ) {
		$tabs['nl_workshop_details'] = array(
			'title'    => __( 'Workshop Details', 'neonlighthk' ),
			'priority' => 20,
			'callback' => 'nl_render_workshop_tab',
		);
	}

	// Rental tab
	if ( 'nl_rental' === $product_type ) {
		$tabs['nl_rental_details'] = array(
			'title'    => __( 'Rental Details', 'neonlighthk' ),
			'priority' => 20,
			'callback' => 'nl_render_rental_tab',
		);
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'nl_custom_product_tabs' );

/**
 * Render workshop details tab content
 */
function nl_render_workshop_tab() {
	global $product;
	?>
	<h2><?php esc_html_e( 'Workshop Details', 'neonlighthk' ); ?></h2>
	<ul class="nl-workshop-meta">
		<li><strong><?php esc_html_e( 'Duration:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_duration' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Instructor:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_instructor' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Max Participants:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_max_participants' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Location:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_location' ) ); ?></li>
	</ul>
	<?php
}

/**
 * Render rental details tab content
 */
function nl_render_rental_tab() {
	global $product;
	?>
	<h2><?php esc_html_e( 'Rental Details', 'neonlighthk' ); ?></h2>
	<ul class="nl-rental-meta">
		<li><strong><?php esc_html_e( 'Daily Price:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_daily_price' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Weekly Price:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_weekly_price' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Deposit:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_deposit_amount' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Dimensions:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_dimensions' ) ); ?></li>
		<li><strong><?php esc_html_e( 'Material:', 'neonlighthk' ); ?></strong> <?php echo esc_html( $product->get_meta( '_nl_material' ) ); ?></li>
	</ul>
	<?php
}

// ────────────────────────────────────────────────────────────────
// 4. Custom Checkout Fields
// ────────────────────────────────────────────────────────────────

/**
 * Add custom checkout fields
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function nl_custom_checkout_fields( $fields ) {

	$fields['billing']['billing_phone_2'] = array(
		'label'       => __( 'Secondary Phone', 'neonlighthk' ),
		'placeholder' => _x( 'Secondary contact number', 'placeholder', 'neonlighthk' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'clear'       => true,
		'priority'    => 105,
	);

	$fields['billing']['billing_delivery_date'] = array(
		'label'       => __( 'Preferred Delivery / Pickup Date', 'neonlighthk' ),
		'placeholder' => _x( 'YYYY-MM-DD', 'placeholder', 'neonlighthk' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'clear'       => true,
		'priority'    => 110,
	);

	$fields['billing']['billing_delivery_notes'] = array(
		'label'       => __( 'Delivery Notes', 'neonlighthk' ),
		'placeholder' => _x( 'Any special instructions', 'placeholder', 'neonlighthk' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'clear'       => true,
		'priority'    => 115,
		'type'        => 'textarea',
	);

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'nl_custom_checkout_fields' );

/**
 * Save custom checkout fields to order meta
 *
 * @param int $order_id Order ID.
 */
function nl_save_custom_checkout_fields( $order_id ) {
	if ( ! empty( $_POST['billing_phone_2'] ) ) {
		update_post_meta( $order_id, '_billing_phone_2', sanitize_text_field( wp_unslash( $_POST['billing_phone_2'] ) ) );
	}
	if ( ! empty( $_POST['billing_delivery_date'] ) ) {
		update_post_meta( $order_id, '_billing_delivery_date', sanitize_text_field( wp_unslash( $_POST['billing_delivery_date'] ) ) );
	}
	if ( ! empty( $_POST['billing_delivery_notes'] ) ) {
		update_post_meta( $order_id, '_billing_delivery_notes', sanitize_textarea_field( wp_unslash( $_POST['billing_delivery_notes'] ) ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'nl_save_custom_checkout_fields' );

/**
 * Display custom checkout fields in admin order details
 *
 * @param WC_Order $order Order object.
 */
function nl_display_custom_order_meta( $order ) {
	$phone_2        = $order->get_meta( '_billing_phone_2' );
	$delivery_date  = $order->get_meta( '_billing_delivery_date' );
	$delivery_notes = $order->get_meta( '_billing_delivery_notes' );

	if ( $phone_2 ) {
		echo '<p><strong>' . esc_html__( 'Secondary Phone:', 'neonlighthk' ) . '</strong> ' . esc_html( $phone_2 ) . '</p>';
	}
	if ( $delivery_date ) {
		echo '<p><strong>' . esc_html__( 'Delivery Date:', 'neonlighthk' ) . '</strong> ' . esc_html( $delivery_date ) . '</p>';
	}
	if ( $delivery_notes ) {
		echo '<p><strong>' . esc_html__( 'Delivery Notes:', 'neonlighthk' ) . '</strong> ' . esc_html( $delivery_notes ) . '</p>';
	}
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'nl_display_custom_order_meta', 10, 1 );

// ────────────────────────────────────────────────────────────────
// 5. Custom Thankyou Page Hook
// ────────────────────────────────────────────────────────────────

/**
 * Add custom content to thankyou page after order details
 *
 * @param int $order_id Order ID.
 */
function nl_custom_thankyou_content( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}
	?>
	<div class="nl-thankyou-custom">
		<h3><?php esc_html_e( 'Thank you for supporting NEON LIGHT HK!', 'neonlighthk' ); ?></h3>
		<p><?php esc_html_e( 'We will contact you shortly to confirm your order details.', 'neonlighthk' ); ?></p>
		<?php if ( $order->has_status( 'on-hold' ) ) : ?>
			<p><?php esc_html_e( 'Your order is on-hold until we confirm payment receipt.', 'neonlighthk' ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'woocommerce_thankyou', 'nl_custom_thankyou_content', 20 );

// ────────────────────────────────────────────────────────────────
// 6. Product Categories in Menu
// ────────────────────────────────────────────────────────────────

/**
 * Show WooCommerce product categories in navigation menus
 *
 * @param array $items Menu items.
 * @param stdClass $args Menu arguments.
 * @return array
 */
function nl_add_product_categories_to_menu( $items, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $items;
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
			'number'     => 10,
		)
	);

	if ( is_wp_error( $categories ) || empty( $categories ) ) {
		return $items;
	}

	foreach ( $categories as $category ) {
		$items[] = (object) array(
			'title'            => $category->name,
			'url'              => get_term_link( $category ),
			'classes'          => array( 'menu-item', 'menu-item-type-taxonomy', 'menu-item-object-product_cat' ),
			'menu_item_parent' => 0,
			'ID'               => 'product-cat-' . $category->term_id,
		);
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'nl_add_product_categories_to_menu', 10, 2 );

// ────────────────────────────────────────────────────────────────
// 7. Misc Helpers
// ────────────────────────────────────────────────────────────────

/**
 * Change number of products per row
 *
 * @return int
 */
function nl_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'nl_loop_columns', 20, 0 );

/**
 * Change number of products per page
 *
 * @param int $cols Columns.
 * @return int
 */
function nl_products_per_page( $cols ) {
	return 12;
}
add_filter( 'loop_shop_per_page', 'nl_products_per_page', 20 );
