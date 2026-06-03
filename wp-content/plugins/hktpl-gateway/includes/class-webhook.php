<?php
/**
 * HKTPL Webhook / Callback Handler — Verified against HKTPL v1.0.62
 *
 * HKTPL sends identical POST callbacks to both:
 * - notifyUrl (webhook endpoint: /wc-api/hktpl-webhook/)
 * - returnUrl (return callback: /wc-api/hktpl-return/)
 *
 * POST params:
 *   merTradeNo, tradeNo, tradeStatus, msg, resultCode, sign
 *
 * Signature verification (HMAC-SHA512):
 * 1. Sort params alphabetically
 * 2. Exclude 'sign' and null/empty values
 * 3. Join as key=value&key2=value2
 * 4. HMAC-SHA512 with API Key
 * 5. Base64-encode
 *
 * @package HKTPL_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTPL_Webhook {

	/**
	 * Handle incoming webhook or return callback.
	 */
	public static function handle() {
		// Only accept POST
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			self::log( 'Callback rejected: method not POST.', 'error' );
			status_header( 405 );
			echo 'Method not allowed';
			exit;
		}

		$params = $_POST;

		$mer_trade_no = isset( $params['merTradeNo'] ) ? sanitize_text_field( $params['merTradeNo'] ) : '';
		$trade_no     = isset( $params['tradeNo'] ) ? sanitize_text_field( $params['tradeNo'] ) : '';
		$trade_status = isset( $params['tradeStatus'] ) ? strtoupper( sanitize_text_field( $params['tradeStatus'] ) ) : '';
		$msg          = isset( $params['msg'] ) ? sanitize_text_field( $params['msg'] ) : '';
		$result_code  = isset( $params['resultCode'] ) ? sanitize_text_field( $params['resultCode'] ) : '';
		$signature    = isset( $params['sign'] ) ? sanitize_text_field( $params['sign'] ) : '';

		if ( empty( $mer_trade_no ) || empty( $signature ) ) {
			self::log( 'Callback rejected: missing merTradeNo or sign.', 'error' );
			status_header( 400 );
			echo 'Missing required parameters';
			exit;
		}

		// Get gateway settings for signature verification
		$gateway = self::get_gateway();
		$api_key = $gateway ? $gateway->api_key : '';

		if ( empty( $api_key ) ) {
			self::log( 'Callback rejected: no API key configured.', 'error' );
			status_header( 503 );
			echo 'Gateway not configured';
			exit;
		}

		// Build sign params (exclude sign, null/empty values)
		$sign_params = array(
			'merTradeNo' => $mer_trade_no,
			'tradeNo'    => $trade_no,
			'tradeStatus'=> $trade_status,
			'msg'        => $msg,
			'resultCode' => $result_code,
		);
		$sign_params = array_filter( $sign_params, function( $v ) { return $v !== null && $v !== ''; } );

		if ( ! HKTPL_Crypto::verify_signature( $sign_params, $signature, $api_key ) ) {
			self::log( 'Callback rejected: signature mismatch.', 'error' );
			status_header( 403 );
			echo 'Signature verification failed';
			exit;
		}

		// Find order
		$orders = wc_get_orders( array(
			'limit'      => 1,
			'meta_query' => array(
				array(
					'key'     => '_hktpl_mer_trade_no',
					'value'   => $mer_trade_no,
					'compare' => '=',
				),
			),
		) );

		if ( empty( $orders ) ) {
			self::log( "Callback: Order not found for merTradeNo {$mer_trade_no}", 'warning' );
			// Return 200 to stop HKTPL retries — order may not exist yet
			status_header( 200 );
			echo 'OK';
			exit;
		}

		$order = $orders[0];

		// Map trade status
		// S = Success (tradeStatus=S, resultCode=000)
		// F = Failed
		// C = Cancelled
		// U = Unknown / processing
		if ( $trade_status === 'S' || $result_code === '000' ) {
			if ( ! $order->is_paid() ) {
				$order->payment_complete( $trade_no );
				$order->add_order_note( sprintf(
					/* translators: 1: Trade number, 2: Message */
					__( 'HKTPL: Payment completed (Trade No: %1$s, Msg: %2$s).', 'hktpl-gateway' ),
					$trade_no,
					$msg
				) );
				self::log( "Order #{$order->get_id()} marked payment_complete.", 'info' );
			} else {
				$order->add_order_note( __( 'HKTPL: Duplicate success callback received.', 'hktpl-gateway' ) );
			}
		} elseif ( in_array( $trade_status, array( 'F', 'C' ), true ) ) {
			$order->update_status( 'failed', sprintf(
				__( 'HKTPL: Payment failed/cancelled (Status: %1$s, Msg: %2$s).', 'hktpl-gateway' ),
				$trade_status,
				$msg
			) );
			self::log( "Order #{$order->get_id()} marked failed (status={$trade_status}).", 'info' );
		} elseif ( $trade_status === 'U' ) {
			$order->add_order_note( sprintf(
				__( 'HKTPL: Payment status unknown/processing (Status: %1$s, Msg: %2$s).', 'hktpl-gateway' ),
				$trade_status,
				$msg
			) );
			self::log( "Order #{$order->get_id()} received unknown status.", 'warning' );
		} else {
			$order->add_order_note( sprintf(
				__( 'HKTPL: Unrecognized trade status %1$s (Msg: %2$s).', 'hktpl-gateway' ),
				$trade_status,
				$msg
			) );
			self::log( "Order #{$order->get_id()} unrecognized status: {$trade_status}", 'warning' );
		}

		$order->update_meta_data( '_hktpl_trade_no', $trade_no );
		$order->update_meta_data( '_hktpl_trade_status', $trade_status );
		$order->update_meta_data( '_hktpl_result_code', $result_code );
		$order->save();

		status_header( 200 );
		echo 'OK';
		exit;
	}

	/**
	 * Get active gateway instance.
	 *
	 * @return HKTPL_Gateway|null
	 */
	private static function get_gateway() {
		if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) {
			return null;
		}
		$gateways = WC()->payment_gateways()->payment_gateways();
		return isset( $gateways['hktpl'] ) ? $gateways['hktpl'] : null;
	}

	/**
	 * Log helper.
	 *
	 * @param string $message Log message.
	 * @param string $level   Log level.
	 */
	private static function log( string $message, string $level = 'info' ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			$logger = wc_get_logger();
			$logger->log( $level, '[HKTPL Webhook] ' . $message, array( 'source' => 'hktpl-gateway' ) );
		}
	}
}
