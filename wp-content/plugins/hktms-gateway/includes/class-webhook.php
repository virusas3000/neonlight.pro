<?php
/**
 * HKTMS Webhook Handler — Verified against HKTMS ePayment Gateway v1.10
 *
 * Handles incoming async order-status callbacks from HKTMS.
 * Endpoint: POST /wc-api/hktms-webhook/
 *
 * HKTMS v1.10 webhook signature:
 *   Header: x-hub-signature: sha512=<hex-digest>
 *   Digest: hexEncode( HMACSHA512( payload_json, base64Decode( app_secret ) ) )
 *
 * @package HKTMS_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTMS_Webhook {

	/**
	 * Process the incoming webhook request.
	 */
	public static function handle(): void {
		// Only accept POST requests.
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			self::log( 'Webhook rejected: method not POST.', 'error' );
			wp_send_json( [ 'status' => 'error', 'message' => 'Method not allowed.' ], 405 );
		}

		// Retrieve gateway settings for verification.
		$gateway = self::get_gateway();
		if ( ! $gateway ) {
			self::log( 'Webhook rejected: HKTMS gateway not found.', 'error' );
			wp_send_json( [ 'status' => 'error', 'message' => 'Gateway unavailable.' ], 503 );
		}

		$raw_body = file_get_contents( 'php://input' );

		// ── Verify HMAC-SHA512 Signature (HKTMS v1.10) ──────────────────
		if ( ! self::verify_signature( $gateway, $raw_body ) ) {
			self::log( 'Webhook rejected: signature verification failed.', 'error' );
			wp_send_json( [ 'status' => 'error', 'message' => 'Unauthorized.' ], 401 );
		}

		// ── Parse JSON body ────────────────────────────────────────────
		$body = json_decode( $raw_body, true );

		if ( json_last_error() !== JSON_ERROR_NONE || empty( $body ) ) {
			self::log( 'Webhook rejected: invalid JSON body. Error: ' . json_last_error_msg(), 'error' );
			wp_send_json( [ 'status' => 'error', 'message' => 'Invalid JSON body.' ], 400 );
		}

		self::log( 'Webhook received: ' . wp_json_encode( $body ), 'info' );

		// ── Extract identifiers ──────────────────────────────────────
		$hktms_order_id = sanitize_text_field( $body['payload']['orderId'] ?? $body['orderId'] ?? '' );
		$merchant_txn   = sanitize_text_field( $body['payload']['merchantTransactionId'] ?? $body['merchantTransactionId'] ?? '' );

		if ( empty( $hktms_order_id ) && empty( $merchant_txn ) ) {
			self::log( 'Webhook rejected: missing order identifiers.', 'error' );
			wp_send_json( [ 'status' => 'error', 'message' => 'Missing order identifiers.' ], 400 );
		}

		// ── Find WooCommerce order ─────────────────────────────────────
		$order = self::find_order( $hktms_order_id, $merchant_txn );

		if ( ! $order ) {
			self::log( sprintf(
				'Webhook: Order not found. HKTMS order ID: %s | Merchant TXN: %s',
				$hktms_order_id,
				$merchant_txn
			), 'error' );
			// Return 200 so HKTMS stops retrying — the order may not exist yet.
			wp_send_json( [ 'status' => 'ok' ], 200 );
		}

		// ── Extract transaction state ──────────────────────────────────
		$transactions = [];
		if ( ! empty( $body['payload']['transactions'] ) && is_array( $body['payload']['transactions'] ) ) {
			$transactions = $body['payload']['transactions'];
		} elseif ( ! empty( $body['transactions'] ) && is_array( $body['transactions'] ) ) {
			$transactions = $body['transactions'];
		}

		if ( empty( $transactions ) ) {
			self::log( 'Webhook: No transactions in payload for order #' . $order->get_id(), 'warning' );
			wp_send_json( [ 'status' => 'ok' ], 200 );
		}

		$latest = end( $transactions );
		$state  = strtoupper( sanitize_text_field( $latest['transactionState'] ?? '' ) );
		$txn_id = sanitize_text_field( $latest['transactionId'] ?? '' );
		$method = sanitize_text_field( $latest['paymentMethod'] ?? $body['payload']['paymentMethod'] ?? 'visamastercard' );

		// Store transaction ID.
		if ( $txn_id ) {
			$order->update_meta_data( '_hktms_transaction_id', $txn_id );
		}
		$order->save_meta_data();

		// ── Map status and update order ────────────────────────────────
		$note_details = sprintf(
			/* translators: 1: Transaction ID, 2: State, 3: Payment method */
			__( 'HKTMS Webhook: Transaction %1$s — State: %2$s (Method: %3$s)', 'hktms-gateway' ),
			$txn_id ?: __( 'N/A', 'hktms-gateway' ),
			$state ?: __( 'UNKNOWN', 'hktms-gateway' ),
			$method ?: __( 'N/A', 'hktms-gateway' )
		);

		// Determine states per method
		$success_states = in_array( $method, [ 'alipayhk', 'alipaycn', 'wechatpay' ], true )
			? [ 'COMPLETED' ]
			: [ 'CAPTURED' ];
		$failure_states = [ 'DECLINED', 'FAILED' ];
		$pending_states = in_array( $method, [ 'alipayhk', 'alipaycn', 'wechatpay' ], true )
			? [ 'PENDING' ]
			: [ 'TEMPLATE' ];

		if ( in_array( $state, $success_states, true ) ) {
			if ( ! $order->is_paid() ) {
				$order->payment_complete( $txn_id );
				$order->add_order_note( $note_details . ' — ' . __( 'Payment captured.', 'hktms-gateway' ) );
				self::log( 'Order #' . $order->get_id() . ' marked as payment_complete.', 'info' );
			} else {
				$order->add_order_note( $note_details . ' — ' . __( 'Already paid; no status change.', 'hktms-gateway' ) );
			}
		} elseif ( in_array( $state, $failure_states, true ) ) {
			$order->update_status( 'failed', $note_details . ' — ' . __( 'Payment declined or failed.', 'hktms-gateway' ) );
			self::log( 'Order #' . $order->get_id() . ' marked as failed.', 'info' );
		} elseif ( in_array( $state, $pending_states, true ) ) {
			if ( ! $order->has_status( 'pending' ) ) {
				$order->update_status( 'pending', $note_details . ' — ' . __( 'Payment pending.', 'hktms-gateway' ) );
				self::log( 'Order #' . $order->get_id() . ' reverted to pending.', 'info' );
			} else {
				$order->add_order_note( $note_details . ' — ' . __( 'Status remains pending.', 'hktms-gateway' ) );
			}
		} else {
			$order->add_order_note( $note_details . ' — ' . __( 'Unrecognized transaction state; no status change.', 'hktms-gateway' ) );
			self::log( 'Order #' . $order->get_id() . ' received unrecognized state: ' . $state, 'warning' );
		}

		// ── Acknowledge ────────────────────────────────────────────────
		wp_send_json( [ 'status' => 'ok' ], 200 );
	}

	/**
	 * Verify the incoming webhook HMAC-SHA512 signature.
	 *
	 * HKTMS v1.10 sends: x-hub-signature: sha512=<hex-digest>
	 * Digest = hexEncode( HMACSHA512( raw_json_payload, base64Decode( app_secret ) ) )
	 *
	 * @param HKTMS_Gateway $gateway Gateway instance.
	 * @param string        $raw_body Raw request body.
	 * @return bool
	 */
	private static function verify_signature( HKTMS_Gateway $gateway, string $raw_body ): bool {
		$testmode = ( 'yes' === $gateway->testmode );
		$headers  = self::get_request_headers();
		$sig_header = $headers['x-hub-signature'] ?? '';

		// No signature header at all.
		if ( empty( $sig_header ) ) {
			if ( $testmode ) {
				self::log( 'Webhook: No x-hub-signature header present (testmode — allowed).', 'warning' );
				return true; // Development only.
			}
			self::log( 'Webhook rejected: no x-hub-signature header in production.', 'error' );
			return false;
		}

		// Extract sha512=<hex> portion
		if ( ! preg_match( '/^sha512=([a-f0-9]{128})$/i', $sig_header, $matches ) ) {
			self::log( 'Webhook: Invalid x-hub-signature format.', 'error' );
			return false;
		}
		$received_sig = strtolower( $matches[1] );

		// Collect every configured App Secret (per-method + legacy).
		$secrets = $gateway->get_all_secrets();

		if ( empty( $secrets ) ) {
			if ( $testmode ) {
				self::log( 'Webhook: No app secret configured (testmode — skipping verification).', 'warning' );
				return true; // Development only.
			}
			self::log( 'Webhook rejected: no app secret configured in production.', 'error' );
			return false;
		}

		// Try every configured secret — a callback is signed with the secret of the
		// method that created the transaction, which is unknown until the body is parsed.
		foreach ( $secrets as $secret_b64 ) {
			$secret = base64_decode( $secret_b64 );
			if ( false === $secret || '' === $secret ) {
				continue;
			}
			$expected_sig = hash_hmac( 'sha512', $raw_body, $secret );
			if ( hash_equals( $expected_sig, $received_sig ) ) {
				return true;
			}
		}

		self::log( 'Webhook: Signature mismatch against all configured secrets.', 'error' );
		return false;
	}

	/**
	 * Locate the WooCommerce order by HKTMS order ID or merchant transaction ID.
	 *
	 * @param string $hktms_order_id HKTMS order identifier.
	 * @param string $merchant_txn   Merchant transaction identifier.
	 * @return WC_Order|null
	 */
	private static function find_order( string $hktms_order_id, string $merchant_txn ): ?WC_Order {
		$order = null;

		if ( $hktms_order_id ) {
			$orders = wc_get_orders( [
				'limit'      => 1,
				'meta_query' => [
					[
						'key'     => '_hktms_order_id',
						'value'   => $hktms_order_id,
						'compare' => '=',
					],
				],
			] );
			if ( ! empty( $orders ) ) {
				$order = reset( $orders );
			}
		}

		if ( ! $order && $merchant_txn ) {
			$orders = wc_get_orders( [
				'limit'      => 1,
				'meta_query' => [
					[
						'key'     => '_hktms_merchant_txn_id',
						'value'   => $merchant_txn,
						'compare' => '=',
					],
				],
			] );
			if ( ! empty( $orders ) ) {
				$order = reset( $orders );
			}
		}

		return $order instanceof WC_Order ? $order : null;
	}

	/**
	 * Retrieve the active HKTMS gateway instance.
	 *
	 * @return HKTMS_Gateway|null
	 */
	private static function get_gateway(): ?HKTMS_Gateway {
		if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) {
			return null;
		}
		$gateways = WC()->payment_gateways()->payment_gateways();
		return $gateways['hktms'] ?? null;
	}

	/**
	 * Fetch normalized request headers.
	 *
	 * @return array<string, string>
	 */
	private static function get_request_headers(): array {
		$headers = [];
		foreach ( $_SERVER as $key => $value ) {
			if ( str_starts_with( $key, 'HTTP_' ) ) {
				// Lowercase the header name so lookups are case-insensitive
				// (PHP normalizes request headers to uppercase HTTP_* keys).
				$header = strtolower( str_replace( '_', '-', substr( $key, 5 ) ) );
				$headers[ $header ] = $value;
			} elseif ( in_array( $key, [ 'CONTENT_TYPE', 'CONTENT_LENGTH' ], true ) ) {
				$headers[ strtolower( str_replace( '_', '-', $key ) ) ] = $value;
			}
		}
		return $headers;
	}

	/**
	 * Write a message to the WooCommerce logger.
	 *
	 * @param string $message Log message.
	 * @param string $level   Log level (error|warning|info|debug).
	 */
	private static function log( string $message, string $level = 'info' ): void {
		if ( function_exists( 'wc_get_logger' ) ) {
			$logger = wc_get_logger();
			$logger->log( $level, '[HKTMS Webhook] ' . $message, [ 'source' => 'hktms-gateway' ] );
		}
	}
}
