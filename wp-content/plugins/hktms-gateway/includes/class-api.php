<?php
/**
 * HKTMS API Client — Verified against HKTMS ePayment Gateway v1.10
 *
 * Handles JWT HS512 authentication and REST API calls.
 *
 * @package HKTMS_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTMS_API {

	private bool $testmode;
	private string $app_id;
	private string $app_secret;
	private string $base_url;

	public function __construct( bool $testmode, string $app_id, string $app_secret ) {
		$this->testmode    = $testmode;
		$this->app_id       = $app_id;
		$this->app_secret    = $app_secret;
		$this->base_url     = $testmode
			? 'https://gateway.sandbox.tapngo.com.hk'
			: 'https://gateway2.tapngo.com.hk';
	}

	/**
	 * Generate JWT Bearer Token (HS512) per HKTMS v1.10 spec.
	 *
	 * Header:  {"alg":"HS512","typ":"JWT"}
	 * Payload: {"sub":"<App_ID>","iat":<epoch>}
	 * Secret:  base64_decode(App Secret)
	 * Sig:     HMACSHA512(base64UrlEncode(header) + "." + base64UrlEncode(payload), base64Decode(secret))
	 */
	public function get_jwt_token(): string {
		$header  = json_encode( [ 'alg' => 'HS512', 'typ' => 'JWT' ] );
		$payload = json_encode( [
			'sub' => $this->app_id,
			'iat' => time(),
		] );

		$header_b64  = $this->base64url_encode( $header );
		$payload_b64 = $this->base64url_encode( $payload );

		$secret    = base64_decode( $this->app_secret );
		$secret_len = strlen( $secret );
		error_log( '[HKTMS JWT] AppID=' . $this->app_id . ' | SecretLen=' . $secret_len . ' | SecretValid=' . ( $secret !== false ? 'yes' : 'no' ) );
		$signature = hash_hmac( 'sha512', $header_b64 . '.' . $payload_b64, $secret, true );
		$sig_b64   = $this->base64url_encode( $signature );

		return $header_b64 . '.' . $payload_b64 . '.' . $sig_b64;
	}

	private function base64url_encode( string $data ): string {
		return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
	}

	/**
	 * Unified API request helper.
	 *
	 * @param string $endpoint API endpoint path (e.g. /ePaymentGateway/visamastercard/v2/transactions/paymentUrl)
	 * @param array  $body     Request body array (will be JSON-encoded)
	 * @param string $method   HTTP method (POST, GET, DELETE)
	 * @return array|WP_Error  WP HTTP response or error
	 */
	private function request( string $endpoint, array $body = [], string $method = 'POST' ) {
		$token = $this->get_jwt_token();
		$url   = $this->base_url . $endpoint;

		$args = [
			'method'  => $method,
			'timeout' => 30,
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
			],
		];

		if ( ! empty( $body ) && in_array( $method, [ 'POST', 'PUT', 'PATCH' ], true ) ) {
			$args['body'] = json_encode( $body );
		}

		// Log the outgoing request so merchants can verify exactly what fields
		// (including mcc / paymentMethod) are sent to HKTMS. Log both to the
		// PHP error log and to WooCommerce's log viewer.
		$log_body = $args['body'] ?? '{}';
		error_log( '[HKTMS REQUEST] ' . $method . ' ' . $url . ' | Body=' . $log_body );
		if ( function_exists( 'wc_get_logger' ) ) {
			wc_get_logger()->info( '[HKTMS REQUEST] ' . $method . ' ' . $url . ' | Body=' . $log_body, [ 'source' => 'hktms-gateway' ] );
		}

		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body_raw = wp_remote_retrieve_body( $response );
		if ( $code >= 400 ) {
			$body_data = json_decode( $body_raw, true );
			$msg = $body_data['message'] ?? __( 'HKTMS API error: HTTP ', 'hktms-gateway' ) . $code;
			error_log( '[HKTMS RAW] URL=' . $url . ' | Code=' . $code . ' | Body=' . $body_raw . ' | JWT=' . substr( $token, 0, 80 ) . '...' );
			return new WP_Error( 'hktms_api_error', $msg . ' | RAW: ' . $body_raw );
		}

		return $response;
	}

	/**
	 * Create Payment URL.
	 *
	 * @param string $method  Payment method key: visamastercard|alipayhk|alipaycn|wechatpay|applepay
	 * @param array  $payload Request payload per HKTMS v1.10 spec
	 * @return array|WP_Error
	 */
	public function create_payment_url( string $method, array $payload ) {
		$endpoint = match( $method ) {
			'alipayhk'       => '/ePaymentGateway/alipayhk/transactions/paymentUrl',
			'alipaycn'       => '/ePaymentGateway/alipaycn/transactions/paymentUrl',
			'wechatpay'      => '/ePaymentGateway/wechatpay/transactions/paymentUrl',
			'applepay'       => '/ePaymentGateway/visamastercard/v2/transactions/paymentUrl', // Apple Pay uses Visa/MC endpoint
			'visamastercard' => '/ePaymentGateway/visamastercard/v2/transactions/paymentUrl',
			default          => '/ePaymentGateway/visamastercard/v2/transactions/paymentUrl',
		};
		return $this->request( $endpoint, $payload );
	}

	/**
	 * Fetch Order Status.
	 *
	 * @param string $method           Payment method key
	 * @param string $order_id         HKTMS order ID
	 * @param string $merchant_txn_id  Merchant transaction ID
	 * @return array|WP_Error
	 */
	public function fetch_order_status( string $method, string $order_id = '', string $merchant_txn_id = '' ) {
		$endpoint = match( $method ) {
			'alipayhk'       => '/ePaymentGateway/alipayhk/transactions/orderStatus',
			'alipaycn'       => '/ePaymentGateway/alipaycn/transactions/orderStatus',
			'wechatpay'      => '/ePaymentGateway/wechatpay/transactions/orderStatus',
			'visamastercard' => '/ePaymentGateway/visamastercard/v2/transactions/orderStatus',
			default          => '/ePaymentGateway/visamastercard/v2/transactions/orderStatus',
		};

		// HKT rejects the orderStatus request if BOTH orderId and
		// merchantTransactionId are present:
		//   {"status":"1","message":"orderId and merchantTransactionId must not
		//    be specified at the same time"}
		// Send only ONE — prefer HKT's canonical orderId, fall back to the
		// merchantTransactionId only when no orderId is available.
		$payload = [];
		if ( $order_id ) {
			$payload['orderId'] = $order_id;
		} elseif ( $merchant_txn_id ) {
			$payload['merchantTransactionId'] = $merchant_txn_id;
		}

		return $this->request( $endpoint, $payload );
	}

	/**
	 * Query Transaction History.
	 *
	 * @param string $method   Payment method key
	 * @param array  $filters  Query filters (date range, etc.)
	 * @return array|WP_Error
	 */
	public function query_history( string $method, array $filters = [] ) {
		$endpoint = match( $method ) {
			'alipayhk'       => '/ePaymentGateway/alipayhk/transactions/queryHistory',
			'alipaycn'       => '/ePaymentGateway/alipaycn/transactions/queryHistory',
			'wechatpay'      => '/ePaymentGateway/wechatpay/transactions/queryHistory',
			'visamastercard' => '/ePaymentGateway/visamastercard/v2/transactions/queryHistory',
			default          => '/ePaymentGateway/visamastercard/v2/transactions/queryHistory',
		};
		return $this->request( $endpoint, $filters );
	}

	/**
	 * Fetch Card Token (Visa/Mastercard only).
	 *
	 * @param string $token_id Token ID
	 * @return array|WP_Error
	 */
	public function fetch_token( string $token_id ) {
		return $this->request( '/ePaymentGateway/visamastercard/v2/tokens/' . urlencode( $token_id ), [], 'GET' );
	}

	/**
	 * Delete Card Token (Visa/Mastercard only).
	 *
	 * @param string $token_id Token ID
	 * @return array|WP_Error
	 */
	public function delete_token( string $token_id ) {
		return $this->request( '/ePaymentGateway/visamastercard/v2/tokens/' . urlencode( $token_id ), [], 'DELETE' );
	}
}
