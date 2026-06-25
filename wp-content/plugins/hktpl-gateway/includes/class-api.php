<?php
/**
 * HKTPL API Client — Verified against HKTPL v1.0.62
 *
 * All Client-to-Server and Server-to-Server endpoints.
 * Content-Type: application/x-www-form-urlencoded for all requests.
 *
 * @package HKTPL_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTPL_API {

	private bool $testmode;
	private string $app_id;
	private string $api_key;
	private string $public_key;
	private string $base_url;

	public function __construct( bool $testmode, string $app_id, string $api_key, string $public_key ) {
		$this->testmode   = $testmode;
		$this->app_id     = $app_id;
		$this->api_key    = $api_key;
		$this->public_key = $public_key;
		$this->base_url  = $testmode ? HKTPL_UAT_BASE_URL : HKTPL_PROD_BASE_URL;
	}

	/**
	 * Do Single Payment (Client-to-Server).
	 *
	 * POST /web/payments
	 * Content-Type: application/x-www-form-urlencoded
	 *
	 * @param array $params Form params: appId, merTradeNo, paymentType, payload, extras?, sign
	 * @return array|WP_Error
	 */
	public function do_single_payment( array $params ) {
		$url  = $this->base_url . '/web/payments';
		$body = http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );

		$args = array(
			'method'  => 'POST',
			'timeout' => 45,
			'headers' => array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
				'Accept'       => 'text/html,application/json',
			),
			'body'    => $body,
		);

		$response = wp_remote_post( $url, $args );
		return $this->handle_response( $response, $url );
	}

	/**
	 * Do Recurrent Payment (Server-to-Server).
	 *
	 * POST /paymentApi/payment/recurrent
	 *
	 * @param array $params appId, recurrentToken, payload, timestamp, sign
	 * @return array|WP_Error
	 */
	public function do_recurrent_payment( array $params ) {
		return $this->post_signed( '/paymentApi/payment/recurrent', $params );
	}

	/**
	 * Invalidate Recurrent Token.
	 *
	 * POST /paymentApi/payment/recurrent/token/invalidation
	 *
	 * @param string $token Recurrent token
	 * @return array|WP_Error
	 */
	public function invalidate_recurrent_token( string $token ) {
		$params = array(
			'appId'          => $this->app_id,
			'recurrentToken' => $token,
			'timestamp'      => (string) ( time() * 1000 ), // Unix ms (spec §5.7.3); seconds give 461.
		);
		$params['sign'] = HKTPL_Crypto::generate_signature( $params, $this->api_key );
		return $this->post_signed( '/paymentApi/payment/recurrent/token/invalidation', $params );
	}

	/**
	 * Query Payment Status (Server-to-Server).
	 *
	 * POST /paymentApi/payment/status
	 *
	 * Per HKTPL v1.0.62 Section 7.4: sends appId + merTradeNo + timestamp + sign directly.
	 * NO accessToken, NO encrypted payload required.
	 *
	 * @param string $mer_trade_no Merchant trade number
	 * @return array|WP_Error
	 */
	public function query_payment_status( string $mer_trade_no ) {
		$params = array(
			'appId'      => $this->app_id,
			'merTradeNo' => $mer_trade_no,
			'timestamp'  => (string) ( time() * 1000 ), // Unix ms (spec §5.7.3); seconds give 461.
		);
		$params['sign'] = HKTPL_Crypto::generate_signature( $params, $this->api_key );
		return $this->post_signed( '/paymentApi/payment/status', $params );
	}

	/**
	 * Log helper — writes to the WooCommerce log (admin-only), source 'hktpl-gateway'.
	 *
	 * @param string $message Log message.
	 * @param string $level   Log level.
	 */
	private static function log( $message, $level = 'info' ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			$logger = wc_get_logger();
			$logger->log( $level, '[HKTPL API] ' . $message, array( 'source' => 'hktpl-gateway' ) );
		}
	}

	/**
	 * Register Access Token (Server-to-Server).
	 *
	 * POST /paymentApi/register/accessToken
	 * Token valid for 30 days.
	 *
	 * @return array|WP_Error
	 */
	public function register_access_token() {
		$params = array(
			'appId'     => $this->app_id,
			'timestamp' => (string) ( time() * 1000 ), // Unix ms (spec §5.7.3); seconds give 461.
		);
		$params['sign'] = HKTPL_Crypto::generate_signature( $params, $this->api_key );
		return $this->post_signed( '/paymentApi/register/accessToken', $params );
	}

	/**
	 * Query Transaction History (Server-to-Server, requires accessToken).
	 *
	 * POST /paymentApi/query/transaction/history
	 *
	 * @param string $access_token Valid access token
	 * @param string $start_date   yyyyMMddHHmmss
	 * @param string $end_date     yyyyMMddHHmmss (max 3 months span)
	 * @param string $mer_trade_no Optional filter
	 * @return array|WP_Error
	 */
	public function query_transaction_history( string $access_token, string $start_date, string $end_date, string $mer_trade_no = '' ) {
		$params = array(
			'accessToken'  => $access_token,
			'startDateTime' => $start_date,
			'endDateTime'   => $end_date,
		);
		if ( $mer_trade_no ) {
			$params['merTradeNo'] = $mer_trade_no;
		}
		return $this->post_signed( '/paymentApi/query/transaction/history', $params );
	}

	/**
	 * Generate Single HKQR Code for Bill Payment.
	 *
	 * POST /paymentApi/create/hkqr
	 * Requires accessToken.
	 *
	 * @param string $access_token Valid access token
	 * @param array  $bill_data    billNo, amount, currency, billDate, etc.
	 * @return array|WP_Error
	 */
	public function generate_hkqr_bill( string $access_token, array $bill_data ) {
		$params = array_merge(
			array( 'accessToken' => $access_token ),
			$bill_data
		);
		return $this->post_signed( '/paymentApi/create/hkqr', $params );
	}

	/**
	 * Generate HKQR Code for Sales Payment.
	 *
	 * POST /paymentApi/create/hkqr/sales
	 * Requires accessToken.
	 *
	 * @param string $access_token Valid access token
	 * @param array  $sales_data   amount, currency, remark, etc.
	 * @return array|WP_Error
	 */
	public function generate_hkqr_sales( string $access_token, array $sales_data ) {
		$params = array_merge(
			array( 'accessToken' => $access_token ),
			$sales_data
		);
		return $this->post_signed( '/paymentApi/create/hkqr/sales', $params );
	}

	/**
	 * Server-to-Server POST with signed payload.
	 *
	 * Adds signature to params, sends as form-urlencoded.
	 *
	 * @param string $endpoint API path
	 * @param array  $params   Parameters (will be signed)
	 * @return array|WP_Error
	 */
	private function post_signed( string $endpoint, array $params ) {
		if ( ! isset( $params['sign'] ) ) {
			$params['sign'] = HKTPL_Crypto::generate_signature( $params, $this->api_key );
		}
		return $this->post_form( $endpoint, $params );
	}

	/**
	 * Raw form-urlencoded POST.
	 *
	 * @param string $endpoint API path
	 * @param array  $params   Form parameters
	 * @return array|WP_Error
	 */
	private function post_form( string $endpoint, array $params ) {
		$url  = $this->base_url . $endpoint;
		$body = http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );

		$args = array(
			'method'  => 'POST',
			'timeout' => 45,
			'headers' => array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
				'Accept'       => 'application/json',
			),
			'body'    => $body,
		);

		$response = wp_remote_post( $url, $args );
		return $this->handle_response( $response, $url );
	}

	/**
	 * Validate and parse API response.
	 *
	 * @param array|WP_Error $response WP HTTP response
	 * @param string         $url      Request URL for logging
	 * @return array|WP_Error
	 */
	private function handle_response( $response, string $url ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( $code >= 400 ) {
			$data = json_decode( $body, true );
			$msg  = isset( $data['content']['engMessage'] ) ? $data['content']['engMessage'] : __( 'HKTPL API error: HTTP ', 'hktpl-gateway' ) . $code;
			return new WP_Error( 'hktpl_api_error', $msg );
		}

		// For /web/payments, response is HTML (redirect page or QR code)
		// We return raw body so gateway can handle it
		return $response;
	}

	/**
	 * Encrypt payment payload.
	 *
	 * @param array $payment_info Payment info array (will be JSON-encoded and encrypted)
	 * @return string|false Base64-encoded ciphertext
	 */
	public function encrypt_payload( array $payment_info ) {
		$json = wp_json_encode( $payment_info );
		return HKTPL_Crypto::rsa_encrypt( $json, $this->public_key );
	}
}
