<?php
/**
 * Payment-received email notifications.
 *
 * On woocommerce_payment_complete:
 *  - Workshop order: HTML email with the booking details (location, date,
 *    time, name, email, phone, number of people, remark) to the customer +
 *    shop, BCC hotmail.
 *  - Product order: HTML invoice to the customer + an order/transaction
 *    notification to the shop (BCC hotmail).
 *
 * Replaces WooCommerce's native "New order", "Processing" and "Completed"
 * customer emails (disabled below) so the customer/shop don't get duplicates.
 *
 * Workshop bookings are created on page-workshop.php, which stashes
 * WC()->session 'nl_booking_id' before redirecting to checkout. That value is
 * normally never attached to the order, so we copy it onto order meta at
 * checkout creation (_nl_booking_id) to bridge the gap to payment time.
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NL_Order_Emails {

	const SHOP_EMAIL = 'www.neonlight.pro@gmail.com';
	const BCC_EMAIL  = 'vickhung3000@hotmail.com';

	/**
	 * Wire up the hooks.
	 */
	public static function init() {
		// 1. Bridge nl_booking_id (session) -> order meta at checkout.
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'attach_booking_id' ), 10, 2 );

		// 2. Disable the native WC payment emails we are replacing.
		add_filter( 'woocommerce_email_enabled_new_order',                 '__return_false' );
		add_filter( 'woocommerce_email_enabled_customer_processing_order', '__return_false' );
		add_filter( 'woocommerce_email_enabled_customer_completed_order',  '__return_false' );

		// 3. Custom payment-complete emails.
		add_action( 'woocommerce_payment_complete', array( __CLASS__, 'on_payment_complete' ) );
	}

	/**
	 * Copy nl_booking_id from the session onto the order so the payment
	 * handler can reach the workshop booking details later.
	 *
	 * @param WC_Order        $order Order being created.
	 * @param array           $data  Checkout POST data.
	 */
	public static function attach_booking_id( $order, $data ) {
		if ( ! function_exists( 'WC' ) || ! WC()->session ) {
			return;
		}
		$booking_id = WC()->session->get( 'nl_booking_id' );
		if ( ! $booking_id ) {
			return;
		}
		// Only bridge the booking onto an order that actually contains a
		// workshop product. Without this guard, a stale nl_booking_id left in
		// the session from a prior workshop-modal visit would leak onto a later
		// regular-product order and misroute its payment email to the workshop
		// template. Consume the session value either way so it never leaks.
		$has_workshop_product = false;
		if ( WC()->cart ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$product = $cart_item['data'] ?? null;
				if ( $product && $product->get_meta( '_nl_workshop_product_id' ) ) {
					$has_workshop_product = true;
					break;
				}
			}
		}
		if ( $has_workshop_product ) {
			$order->update_meta_data( '_nl_booking_id', (int) $booking_id );
		}
		WC()->session->__unset( 'nl_booking_id' );
	}

	/**
	 * Send the custom emails when an order is paid.
	 *
	 * Deduped via the _nl_payment_email_sent meta flag — both the webhook and
	 * the status-query path can call payment_complete() for the same order.
	 *
	 * @param int $order_id Order ID.
	 */
	public static function on_payment_complete( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		if ( $order->get_meta( '_nl_payment_email_sent' ) ) {
			return;
		}

		$booking_id  = (int) $order->get_meta( '_nl_booking_id' );
		// A workshop email only makes sense when a real booking exists (with
		// location/date/time/etc.), so require BOTH a booking id and a workshop
		// product in the cart. A workshop product bought directly without the
		// booking modal has no booking id and gets the product invoice instead
		// (no "Not provided" placeholders).
		$is_workshop = $booking_id && self::order_has_workshop_product( $order );

		if ( $is_workshop ) {
			self::send_workshop_email( $order, $booking_id );
		} else {
			self::send_product_emails( $order );
		}

		$order->update_meta_data( '_nl_payment_email_sent', time() );
		$order->save();
	}

	/**
	 * Is any line item's product tagged as a workshop product?
	 *
	 * @param WC_Order $order Order.
	 * @return bool
	 */
	private static function order_has_workshop_product( $order ) {
		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( $product && $product->get_meta( '_nl_workshop_product_id' ) ) {
				return true;
			}
		}
		return false;
	}

	// ------------------------------------------------------------------
	// Workshop email
	// ------------------------------------------------------------------

	/**
	 * Send the workshop confirmation email (customer + shop, BCC hotmail).
	 *
	 * @param WC_Order $order      Paid order.
	 * @param int      $booking_id nl_booking CPT ID (0 if bought without the modal).
	 */
	private static function send_workshop_email( $order, $booking_id ) {
		$b = array();
		if ( $booking_id ) {
			$b = array(
				'title'    => get_post_meta( $booking_id, '_nl_workshop_title',   true ),
				'location' => get_post_meta( $booking_id, '_nl_location_name',    true ),
				'address'  => get_post_meta( $booking_id, '_nl_location_address', true ),
				'date'     => get_post_meta( $booking_id, '_nl_booking_date',     true ),
				'time'     => get_post_meta( $booking_id, '_nl_booking_time',     true ),
				'name'     => get_post_meta( $booking_id, '_nl_customer_name',    true ),
				'email'    => get_post_meta( $booking_id, '_nl_customer_email',   true ),
				'phone'    => get_post_meta( $booking_id, '_nl_customer_phone',   true ),
				'pax'      => get_post_meta( $booking_id, '_nl_group_size',       true ),
				'remarks'  => get_post_meta( $booking_id, '_nl_remarks',          true ),
			);
		}

		// Fall back to the order's billing fields where the booking is silent.
		$name  = ( $b['name']  ?? '' ) ?: $order->get_formatted_billing_full_name();
		$email = ( $b['email'] ?? '' ) ?: $order->get_billing_email();
		$phone = ( $b['phone'] ?? '' ) ?: $order->get_billing_phone();
		$pax   = ( $b['pax']   ?? '' ) ?: self::first_item_qty( $order );
		$title = ( $b['title'] ?? '' ) ?: self::first_item_name( $order );

		$loc = '';
		if ( ! empty( $b['location'] ) ) {
			$loc = ! empty( $b['address'] ) ? $b['location'] . ' — ' . $b['address'] : $b['location'];
		}

		$na  = __( 'Not provided', 'neonlighthk' );
		$rows = array(
			__( 'Workshop', 'neonlighthk' )         => $title ?: $na,
			__( 'Location', 'neonlighthk' )         => $loc ?: $na,
			__( 'Date', 'neonlighthk' )             => ( $b['date'] ?? '' ) ?: $na,
			__( 'Time', 'neonlighthk' )             => ( $b['time'] ?? '' ) ?: $na,
			__( 'Name', 'neonlighthk' )             => $name ?: $na,
			__( 'Email', 'neonlighthk' )            => $email ?: $na,
			__( 'Phone', 'neonlighthk' )            => $phone ?: $na,
			__( 'Number of people', 'neonlighthk' ) => $pax ?: $na,
			__( 'Remarks', 'neonlighthk' )          => ( $b['remarks'] ?? '' ) ?: $na,
		);

		$subject = sprintf( __( 'Workshop Booking Confirmed — %s', 'neonlighthk' ), $title ?: __( 'Workshop', 'neonlighthk' ) );
		$body    = self::wrap_html(
			__( 'Thank you! Your workshop booking is confirmed and payment has been received.', 'neonlighthk' ),
			self::build_kv_table( $rows ),
			sprintf( __( 'Total paid: %s', 'neonlighthk' ), wp_strip_all_tags( $order->get_formatted_order_total() ) )
		);

		$to = array( self::SHOP_EMAIL );
		if ( $email && is_email( $email ) ) {
			$to[] = $email;
		}
		self::mail( $to, $subject, $body, true );

		if ( $booking_id ) {
			update_post_meta( $booking_id, '_nl_booking_status', 'Paid' );
		}
	}

	// ------------------------------------------------------------------
	// Product emails
	// ------------------------------------------------------------------

	/**
	 * Send the shop notification + customer invoice for a product order.
	 *
	 * @param WC_Order $order Paid order.
	 */
	private static function send_product_emails( $order ) {
		$number      = $order->get_order_number();
		$order_table = self::build_order_table( $order );
		$cust_block  = self::build_customer_block( $order );
		$txn_block   = self::build_transaction_block( $order );
		$body        = $order_table . $cust_block . $txn_block;

		// Shop notification.
		$shop_body = self::wrap_html(
			sprintf( __( 'A new order has been paid.', 'neonlighthk' ) ),
			$body,
			''
		);
		self::mail( self::SHOP_EMAIL, sprintf( __( 'New Paid Order #%s', 'neonlighthk' ), $number ), $shop_body, true );

		// Customer invoice.
		$cust_email = $order->get_billing_email();
		if ( $cust_email && is_email( $cust_email ) ) {
			$cust_body = self::wrap_html(
				sprintf( __( 'Thank you for your order #%s. Your payment has been received.', 'neonlighthk' ), $number ),
				$body,
				''
			);
			self::mail( $cust_email, sprintf( __( 'Your Order #%s — Invoice', 'neonlighthk' ), $number ), $cust_body, false );
		}
	}

	// ------------------------------------------------------------------
	// Builders
	// ------------------------------------------------------------------

	/**
	 * Order details table (items, number, date, total).
	 *
	 * @param WC_Order $order Order.
	 * @return string HTML.
	 */
	private static function build_order_table( $order ) {
		$rows = '';
		foreach ( $order->get_items() as $item ) {
			$qty  = (int) $item->get_quantity();
			$rows .= '<tr>'
				. '<td style="padding:8px 12px;border-bottom:1px solid #eee;">' . esc_html( $item->get_name() ) . ( $qty > 1 ? ' × ' . $qty : '' ) . '</td>'
				. '<td style="padding:8px 12px;border-bottom:1px solid #eee;text-align:right;white-space:nowrap;">' . wp_kses_post( $order->get_formatted_line_subtotal( $item ) ) . '</td>'
				. '</tr>';
		}

		$t  = '<h3 style="margin:0 0 8px;font-size:15px;">' . esc_html__( 'Order details', 'neonlighthk' ) . '</h3>';
		$t .= '<table style="width:100%;border-collapse:collapse;font-size:14px;margin:0 0 12px;"><tbody>' . $rows . '</tbody></table>';
		$t .= '<p style="margin:0 0 4px;font-size:14px;">' . esc_html__( 'Order number:', 'neonlighthk' ) . ' <strong>' . esc_html( $order->get_order_number() ) . '</strong></p>';
		$t .= '<p style="margin:0 0 4px;font-size:14px;">' . esc_html__( 'Date:', 'neonlighthk' ) . ' <strong>' . esc_html( wc_format_datetime( $order->get_date_created() ) ) . '</strong></p>';
		$t .= '<p style="margin:0 0 4px;font-size:14px;">' . esc_html__( 'Total:', 'neonlighthk' ) . ' <strong>' . wp_kses_post( $order->get_formatted_order_total() ) . '</strong></p>';
		return $t;
	}

	/**
	 * Transaction details block (payment method + transaction/trade id).
	 *
	 * @param WC_Order $order Order.
	 * @return string HTML.
	 */
	private static function build_transaction_block( $order ) {
		$out    = '';
		$method = $order->get_payment_method_title();
		$txn    = $order->get_transaction_id();
		$trade  = $order->get_meta( '_hktpl_trade_no' );

		if ( $method ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Payment method:', 'neonlighthk' ) . '</strong> ' . esc_html( $method ) . '</p>';
		}
		if ( $txn ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Transaction ID:', 'neonlighthk' ) . '</strong> ' . esc_html( $txn ) . '</p>';
		}
		if ( $trade && $trade !== $txn ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Trade No:', 'neonlighthk' ) . '</strong> ' . esc_html( $trade ) . '</p>';
		}

		return $out ? '<div style="margin:12px 0 0;padding-top:8px;border-top:1px solid #eee;">' . $out . '</div>' : '';
	}

	/**
	 * Customer details block (name, email, phone, billing/shipping address).
	 *
	 * @param WC_Order $order Order.
	 * @return string HTML.
	 */
	private static function build_customer_block( $order ) {
		$out   = '';
		$name  = $order->get_formatted_billing_full_name();
		$email = $order->get_billing_email();
		$phone = $order->get_billing_phone();

		// Formatted billing address WITHOUT the name (name is shown above) so
		// it is not duplicated. WC_Countries::get_formatted_address() returns
		// <br/>-separated HTML, kept via wp_kses_post.
		$addr = '';
		if ( function_exists( 'WC' ) && WC()->countries ) {
			$addr = WC()->countries->get_formatted_address(
				array(
					'address_1' => $order->get_billing_address_1(),
					'address_2' => $order->get_billing_address_2(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'postcode'  => $order->get_billing_postcode(),
					'country'   => $order->get_billing_country(),
				)
			);
		}

		if ( $name ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Name:', 'neonlighthk' ) . '</strong> ' . esc_html( $name ) . '</p>';
		}
		if ( $email ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Email:', 'neonlighthk' ) . '</strong> ' . esc_html( $email ) . '</p>';
		}
		if ( $phone ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Phone:', 'neonlighthk' ) . '</strong> ' . esc_html( $phone ) . '</p>';
		}
		if ( $addr ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Address:', 'neonlighthk' ) . '</strong><br>' . wp_kses_post( $addr ) . '</p>';
		}

		// Shipping address, only if it was entered and differs from billing.
		$shipping = $order->get_formatted_shipping_address();
		if ( $shipping && $shipping !== $order->get_formatted_billing_address() ) {
			$out .= '<p style="margin:0 0 4px;font-size:14px;"><strong>' . esc_html__( 'Shipping address:', 'neonlighthk' ) . '</strong><br>' . wp_kses_post( $shipping ) . '</p>';
		}

		if ( ! $out ) {
			return '';
		}
		$h = '<h3 style="margin:0 0 8px;font-size:15px;">' . esc_html__( 'Customer details', 'neonlighthk' ) . '</h3>';
		return '<div style="margin:12px 0 0;padding-top:8px;border-top:1px solid #eee;">' . $h . $out . '</div>';
	}

	/**
	 * Two-column key/value table for the workshop email.
	 *
	 * @param array $rows label => value.
	 * @return string HTML.
	 */
	private static function build_kv_table( $rows ) {
		$t = '<table style="width:100%;border-collapse:collapse;font-size:14px;margin:8px 0 0;">';
		foreach ( $rows as $label => $value ) {
			$t .= '<tr>'
				. '<th style="text-align:left;padding:8px 12px;border-bottom:1px solid #eee;width:40%;color:#666;font-weight:normal;">' . esc_html( $label ) . '</th>'
				. '<td style="padding:8px 12px;border-bottom:1px solid #eee;">' . esc_html( $value ) . '</td>'
				. '</tr>';
		}
		$t .= '</table>';
		return $t;
	}

	/**
	 * Wrap intro/content/footer in a simple branded HTML shell.
	 *
	 * @param string $intro   Lead paragraph (escaped here).
	 * @param string $content HTML content (already built/sanitized).
	 * @param string $footer  Footer line (escaped here), '' for none.
	 * @return string HTML.
	 */
	private static function wrap_html( $intro, $content, $footer ) {
		$html  = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#222;">';
		$html .= '<div style="max-width:560px;margin:24px auto;background:#fff;border-radius:8px;overflow:hidden;border:1px solid #eee;">';
		$html .= '<div style="background:#003B5C;color:#fff;padding:20px 24px;font-size:18px;font-weight:bold;">Neonlight.pro</div>';
		$html .= '<div style="padding:24px;">';
		if ( $intro ) {
			$html .= '<p style="margin:0 0 16px;font-size:15px;line-height:1.5;">' . esc_html( $intro ) . '</p>';
		}
		$html .= $content;
		if ( $footer ) {
			$html .= '<p style="margin:16px 0 0;font-size:15px;line-height:1.5;"><strong>' . esc_html( $footer ) . '</strong></p>';
		}
		$html .= '</div></div></body></html>';
		return $html;
	}

	// ------------------------------------------------------------------
	// Helpers
	// ------------------------------------------------------------------

	/**
	 * Send an HTML email, optionally with the shop BCC.
	 *
	 * @param string|array $to       Recipient(s).
	 * @param string       $subject  Subject.
	 * @param string       $html     HTML body.
	 * @param bool         $with_bcc Add the shop BCC.
	 * @return bool
	 */
	private static function mail( $to, $subject, $html, $with_bcc ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		if ( $with_bcc ) {
			$headers[] = 'Bcc: ' . self::BCC_EMAIL;
		}
		return wp_mail( $to, $subject, $html, $headers );
	}

	/**
	 * First line item's name (workshop orders have a single line item).
	 *
	 * @param WC_Order $order Order.
	 * @return string
	 */
	private static function first_item_name( $order ) {
		foreach ( $order->get_items() as $item ) {
			return $item->get_name();
		}
		return '';
	}

	/**
	 * First line item's quantity (= number of people for a workshop).
	 *
	 * @param WC_Order $order Order.
	 * @return int|string
	 */
	private static function first_item_qty( $order ) {
		foreach ( $order->get_items() as $item ) {
			return (int) $item->get_quantity();
		}
		return '';
	}
}

NL_Order_Emails::init();