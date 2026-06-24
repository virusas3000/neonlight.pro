<?php
/**
 * HKTPL WooCommerce Payment Gateway — Verified against HKTPL v1.0.62
 *
 * Supports: Tap & Go (mobile app launch + QR code), FPS (QR code)
 * Payment flow: Desktop → QR code | Mobile → App launch or web payment
 *
 * @package HKTPL_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTPL_Gateway extends WC_Payment_Gateway {

	public $testmode;
	public $app_id;
	public $api_key;
	public $public_key;
	public $payment_network;
	public $lang;
	public $auto_capture;

	public function __construct() {
		$this->id                 = 'hktpl';
		$this->has_fields         = true;
		$this->method_title       = __( 'Tap & Go / FPS (HKTPL)', 'hktpl-gateway' );
		$this->method_description = __( 'Accept payments via Tap & Go and FPS using HKTPL gateway. Desktop users see a QR code; mobile users launch the Tap & Go app.', 'hktpl-gateway' );
		$this->supports           = array( 'products', 'refunds' );

		$this->init_form_fields();
		$this->init_settings();

		$this->title            = $this->get_option( 'title', __( 'Tap & Go / FPS', 'hktpl-gateway' ) );
		$this->description      = $this->get_option( 'description' );
		$this->enabled          = $this->get_option( 'enabled', 'no' );
		$this->testmode         = 'yes' === $this->get_option( 'testmode', 'yes' );
		$this->app_id           = $this->get_option( 'app_id' );
		$this->api_key          = $this->get_option( 'api_key' );
		$this->public_key       = $this->get_option( 'public_key' );
		$this->payment_network  = $this->get_option( 'payment_network', 'ALL' );
		$this->lang             = $this->get_option( 'lang', 'en' );
		$this->auto_capture     = 'yes' === $this->get_option( 'auto_capture', 'yes' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
	}

	/**
	 * Admin settings form.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'hktpl-gateway' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable HKTPL Payment Gateway', 'hktpl-gateway' ),
				'default' => 'yes',
			),
			'testmode' => array(
				'title'       => __( 'Test Mode', 'hktpl-gateway' ),
				'type'        => 'checkbox',
				'label'       => __( 'Use UAT (Sandbox) Environment', 'hktpl-gateway' ),
				'description' => __( 'Uses gateway.sandbox.tapngo.com.hk. Disable for production.', 'hktpl-gateway' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'title' => array(
				'title'       => __( 'Title', 'hktpl-gateway' ),
				'type'        => 'text',
				'description' => __( 'Payment method title shown at checkout.', 'hktpl-gateway' ),
				'default'     => __( 'Tap & Go / FPS', 'hktpl-gateway' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'hktpl-gateway' ),
				'type'        => 'textarea',
				'description' => __( 'Shown under the payment method at checkout.', 'hktpl-gateway' ),
				'default'     => __( 'Pay securely with Tap & Go or FPS via HKTPL.', 'hktpl-gateway' ),
			),
			'app_id' => array(
				'title'       => __( 'App ID', 'hktpl-gateway' ),
				'type'        => 'text',
				'description' => __( 'Your HKTPL Application ID (e.g. 70136705).', 'hktpl-gateway' ),
				'desc_tip'    => true,
			),
			'api_key' => array(
				'title'       => __( 'API Key', 'hktpl-gateway' ),
				'type'        => 'password',
				'description' => __( 'Your HKTPL API Key for HMAC-SHA512 signing. Keep secret.', 'hktpl-gateway' ),
				'desc_tip'    => true,
			),
			'public_key' => array(
				'title'       => __( 'RSA Public Key', 'hktpl-gateway' ),
				'type'        => 'textarea',
				'description' => __( 'HKTPL RSA public key (Base64-encoded X.509, 4096-bit) for payload encryption.', 'hktpl-gateway' ),
				'desc_tip'    => true,
			),
			'payment_network' => array(
				'title'       => __( 'Payment Network', 'hktpl-gateway' ),
				'type'        => 'select',
				'description' => __( 'Which payment networks to accept.', 'hktpl-gateway' ),
				'options'     => array(
					'ALL'    => __( 'All (Tap & Go + FPS)', 'hktpl-gateway' ),
					'FPS'    => __( 'FPS only', 'hktpl-gateway' ),
					'Tapngo' => __( 'Tap & Go only', 'hktpl-gateway' ),
				),
				'default'     => 'ALL',
				'desc_tip'    => true,
			),
			'lang' => array(
				'title'       => __( 'Language', 'hktpl-gateway' ),
				'type'        => 'select',
				'description' => __( 'Payment page language.', 'hktpl-gateway' ),
				'options'     => array(
					'en' => 'English',
					'zh' => '繁體中文',
				),
				'default'     => 'en',
				'desc_tip'    => true,
			),
			'auto_capture' => array(
				'title'       => __( 'Auto Capture', 'hktpl-gateway' ),
				'type'        => 'checkbox',
				'label'       => __( 'Automatically mark orders complete on successful payment.', 'hktpl-gateway' ),
				'default'     => 'yes',
			),
		);
	}

	/**
	 * Payment fields at checkout.
	 */
	public function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wp_kses_post( $this->description ) );
		}

		$options = array();
		if ( in_array( $this->payment_network, array( 'ALL', 'Tapngo' ), true ) ) {
			$options['tapngo'] = array(
				'label' => __( 'Tap & Go', 'hktpl-gateway' ),
				'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 24" height="24"><rect fill="#E4002B" rx="4" width="60" height="24"/><text x="30" y="17" fill="#fff" font-size="11" font-family="Arial,sans-serif" font-weight="bold" text-anchor="middle">Tap &amp; Go</text></svg>',
			);
		}
		if ( in_array( $this->payment_network, array( 'ALL', 'FPS' ), true ) ) {
			$options['fps'] = array(
				'label' => __( 'FPS', 'hktpl-gateway' ),
				'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 24" height="24"><rect fill="#003B5C" rx="4" width="60" height="24"/><text x="30" y="17" fill="#fff" font-size="11" font-family="Arial,sans-serif" font-weight="bold" text-anchor="middle">FPS</text></svg>',
			);
		}
		?>
		<div class="hktpl-payment-options">
			<p><?php esc_html_e( 'Choose your payment method:', 'hktpl-gateway' ); ?></p>
			<?php foreach ( $options as $key => $opt ) : ?>
				<label class="hktpl-option">
					<input type="radio" name="hktpl_method" value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, array_key_first( $options ) ); ?> />
					<span class="hktpl-icon"><?php echo $opt['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span><?php echo esc_html( $opt['label'] ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
		<style>
			.hktpl-payment-options { margin-top: 10px; }
			.hktpl-option { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; cursor: pointer; }
			.hktpl-option .hktpl-icon svg { height: 24px; width: auto; display: block; }
		</style>
		<?php
	}

	/**
	 * Process payment — build encrypted payload and submit to HKTPL.
	 *
	 * POST /web/payments (Client-to-Server)
	 * Content-Type: application/x-www-form-urlencoded
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wc_add_notice( __( 'Order not found.', 'hktpl-gateway' ), 'error' );
			return array( 'result' => 'failure' );
		}

		if ( empty( $this->app_id ) || empty( $this->api_key ) || empty( $this->public_key ) ) {
			wc_add_notice( __( 'HKTPL gateway is not configured. Please contact the store admin.', 'hktpl-gateway' ), 'error' );
			return array( 'result' => 'failure' );
		}

		$selected_method = isset( $_POST['hktpl_method'] ) ? sanitize_text_field( wp_unslash( $_POST['hktpl_method'] ) ) : 'tapngo';

		// Generate unique merchant trade number
		$mer_trade_no = (string) $order->get_id() . '-' . wp_generate_password( 6, false );
		$order->update_meta_data( '_hktpl_mer_trade_no', $mer_trade_no );
		$order->update_meta_data( '_hktpl_method', $selected_method );
		$order->save();

		$api = new HKTPL_API( $this->testmode, $this->app_id, $this->api_key, $this->public_key );

		// Build paymentInfo (payload content) per HKTPL v1.0.62 section 5.4
		$total = number_format( (float) $order->get_total(), 2, '.', '' );
		$payment_info = array(
			'merTradeNo' => $mer_trade_no,
			'currency'   => $order->get_currency(),
			'totalPrice' => $total,
			'returnUrl'  => add_query_arg( 'wc-api', 'hktpl-return', home_url( '/' ) ),
			'lang'       => $this->lang,
		);

		if ( $this->payment_network !== 'ALL' ) {
			$payment_info['paymentNetwork'] = $this->payment_network;
		}

		// Optional remark
		$remark = $order->get_customer_note();
		if ( $remark ) {
			$payment_info['remark'] = substr( $remark, 0, 256 );
		}

		// Encrypt payload
		$encrypted_payload = $api->encrypt_payload( $payment_info );
		if ( false === $encrypted_payload ) {
			wc_add_notice( __( 'Failed to encrypt payment data. Please contact the store admin.', 'hktpl-gateway' ), 'error' );
			return array( 'result' => 'failure' );
		}

		// Build extras (notifyUrl + customer contact)
		$extras = array();
		$extras['notifyUrl'] = add_query_arg( 'wc-api', 'hktpl-webhook', home_url( '/' ) );
		$email = $order->get_billing_email();
		if ( $email ) {
			$extras['custMail'] = $email;
		}
		$phone = $order->get_billing_phone();
		if ( $phone ) {
			$extras['custPhone'] = $phone;
		}
		$encrypted_extras = $api->encrypt_payload( $extras );
		if ( false === $encrypted_extras ) {
			$encrypted_extras = '';
		}

		// Build form params for /web/payments
		$form_params = array(
			'appId'       => $this->app_id,
			'merTradeNo'  => $mer_trade_no,
			'paymentType' => 'S',
			'payload'     => $encrypted_payload,
		);
		if ( $encrypted_extras ) {
			$form_params['extras'] = $encrypted_extras;
		}

		// Generate HMAC-SHA512 signature
		$form_params['sign'] = HKTPL_Crypto::generate_signature( $form_params, $this->api_key );

		// Submit payment
		$response = $api->do_single_payment( $form_params );

		if ( is_wp_error( $response ) ) {
			wc_add_notice( $response->get_error_message(), 'error' );
			return array( 'result' => 'failure' );
		}

		$body = wp_remote_retrieve_body( $response );
		$code = wp_remote_retrieve_response_code( $response );

		// /web/payments returns HTML (200) with redirect/QR page, or JSON error
		if ( $code >= 400 ) {
			wc_add_notice( __( 'Unable to initiate payment. Please try again.', 'hktpl-gateway' ), 'error' );
			return array( 'result' => 'failure' );
		}

		// Rewrite relative URLs in HKTPL HTML to absolute (images/QR code src)
		$base = $this->testmode ? HKTPL_UAT_BASE_URL : HKTPL_PROD_BASE_URL;
		$body = preg_replace_callback(
			'~(?<=\s)(src|href)=([\'"])(?!https?://|//|data:|mailto:|#)/?~i',
			function ( $m ) use ( $base ) {
				return $m[1] . '=' . $m[2] . $base . '/';
			},
			$body
		);
		// Also handle srcset and CSS url() references
		$body = preg_replace_callback(
			'~url\(([\'"]?)\s*/~i',
			function ( $m ) use ( $base ) {
				return 'url(' . $m[1] . $base . '/';
			},
			$body
		);

		// Proxy broken HKTPL static image URLs to local copies
		$plugin_img_url = HKTPL_GATEWAY_PLUGIN_URL . 'assets/img/';
		$body = str_replace(
			$base . '/static/img/tapngoLogo.svg',
			$plugin_img_url . 'tapngoLogo.svg?v=2',
			$body
		);
		$body = str_replace(
			$base . '/static/img/fpsLogo.svg',
			$plugin_img_url . 'fpsLogo.svg?v=2',
			$body
		);
		$body = str_replace(
			$base . '/static/img/hktLogo.svg',
			$plugin_img_url . 'hktLogo.svg?v=2',
			$body
		);

		// Store the HTML response for receipt page display
		$order->update_meta_data( '_hktpl_payment_html', $body );
		$order->update_status( 'pending', __( 'Awaiting HKTPL payment completion.', 'hktpl-gateway' ) );
		$order->save();

		wc_reduce_stock_levels( $order_id );
		WC()->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true ),
		);
	}

	/**
	 * Receipt page — displays the HKTPL payment page (QR code or app launch).
	 */
	public function receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$html = $order->get_meta( '_hktpl_payment_html' );
		if ( $html ) {
			// HKTPL returns an HTML page with QR code or redirect
			echo $html;
		} else {
			echo '<p>' . esc_html__( 'Payment initialization failed. Please contact support.', 'hktpl-gateway' ) . '</p>';
		}
	}

	/**
	 * Handle customer return from HKTPL and server callback.
	 *
	 * HKTPL sends POST callback to returnUrl with form data:
	 * merTradeNo, tradeNo, tradeStatus, msg, resultCode, sign
	 */
	public function handle_return() {
		$method = $_SERVER['REQUEST_METHOD'];

		// Server callback (POST)
		if ( $method === 'POST' ) {
			$this->handle_post_callback();
			return;
		}

		// Browser redirect (GET) — fall back to order received page
		$order_id = absint( $_GET['order_id'] ?? 0 );
		$key      = sanitize_text_field( $_GET['key'] ?? '' );
		$order    = wc_get_order( $order_id );

		if ( ! $order || $order->get_order_key() !== $key ) {
			wp_safe_redirect( wc_get_checkout_url() );
			exit;
		}

		// Check order status — if paid, go to thank you; if not, query API
		if ( $order->is_paid() ) {
			wp_safe_redirect( $this->get_return_url( $order ) );
			exit;
		}

		$mer_trade_no = $order->get_meta( '_hktpl_mer_trade_no' );
		if ( $mer_trade_no ) {
			$api    = new HKTPL_API( $this->testmode, $this->app_id, $this->api_key, $this->public_key );
			$status = $api->query_payment_status( $mer_trade_no );
			if ( ! is_wp_error( $status ) ) {
				$body = json_decode( wp_remote_retrieve_body( $status ), true );
				$this->update_order_from_status( $order, $body );
			}
		}

		if ( $order->is_paid() ) {
			wp_safe_redirect( $this->get_return_url( $order ) );
		} else {
			wc_add_notice( __( 'Payment was not successful. Please try again.', 'hktpl-gateway' ), 'error' );
			wp_safe_redirect( wc_get_checkout_url() );
		}
		exit;
	}

	/**
	 * Handle POST callback from HKTPL server.
	 */
	private function handle_post_callback() {
		$params = $_POST;

		$mer_trade_no = isset( $params['merTradeNo'] ) ? sanitize_text_field( $params['merTradeNo'] ) : '';
		$trade_no     = isset( $params['tradeNo'] ) ? sanitize_text_field( $params['tradeNo'] ) : '';
		$trade_status = isset( $params['tradeStatus'] ) ? strtoupper( sanitize_text_field( $params['tradeStatus'] ) ) : '';
		$msg          = isset( $params['msg'] ) ? sanitize_text_field( $params['msg'] ) : '';
		$result_code  = isset( $params['resultCode'] ) ? sanitize_text_field( $params['resultCode'] ) : '';
		$signature    = isset( $params['sign'] ) ? sanitize_text_field( $params['sign'] ) : '';

		if ( empty( $mer_trade_no ) || empty( $signature ) ) {
			status_header( 400 );
			echo 'Missing required parameters';
			exit;
		}

		// Verify signature
		$sign_params = array(
			'merTradeNo' => $mer_trade_no,
			'tradeNo'    => $trade_no,
			'tradeStatus'=> $trade_status,
			'msg'        => $msg,
			'resultCode' => $result_code,
		);
		// Remove null/empty values before signing per spec
		$sign_params = array_filter( $sign_params, function( $v ) { return $v !== null && $v !== ''; } );

		if ( ! HKTPL_Crypto::verify_signature( $sign_params, $signature, $this->api_key ) ) {
			status_header( 403 );
			echo 'Signature verification failed';
			exit;
		}

		// Find order
		$orders = wc_get_orders( array(
			'limit'        => 1,
			'meta_query'   => array(
				array(
					'key'     => '_hktpl_mer_trade_no',
					'value'   => $mer_trade_no,
					'compare' => '=',
				),
			),
		) );

		if ( empty( $orders ) ) {
			status_header( 404 );
			echo 'Order not found';
			exit;
		}

		$order = $orders[0];

		// Map trade status
		if ( $trade_status === 'S' || $result_code === '000' ) {
			if ( ! $order->is_paid() ) {
				$order->payment_complete( $trade_no );
				$order->add_order_note( sprintf(
					/* translators: 1: Tap & Go trade number, 2: result message */
					__( 'HKTPL payment completed (Trade No: %1$s, Msg: %2$s).', 'hktpl-gateway' ),
					$trade_no,
					$msg
				) );
			}
		} elseif ( in_array( $trade_status, array( 'F', 'C', 'U' ), true ) ) {
			// F = Failed, C = Cancelled, U = Unknown
			$order->update_status( 'failed', sprintf(
				__( 'HKTPL payment failed/cancelled (Status: %1$s, Msg: %2$s).', 'hktpl-gateway' ),
				$trade_status,
				$msg
			) );
		} else {
			$order->add_order_note( sprintf(
				__( 'HKTPL callback received: Status %1$s, Msg %2$s.', 'hktpl-gateway' ),
				$trade_status,
				$msg
			) );
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
	 * Update order from Query Payment Status API response.
	 *
	 * @param WC_Order $order WooCommerce order.
	 * @param array    $body  API response body.
	 */
	private function update_order_from_status( $order, $body ) {
		if ( empty( $body ) || ! is_array( $body ) ) {
			return;
		}

		$result_code  = isset( $body['content']['resultCode'] ) ? $body['content']['resultCode'] : '';
		$trade_status = isset( $body['content']['tradeStatus'] ) ? strtoupper( $body['content']['tradeStatus'] ) : '';
		$trade_no     = isset( $body['content']['tradeNo'] ) ? $body['content']['tradeNo'] : '';

		if ( $result_code === '0' && $trade_status === 'TRADE_FINISHED' ) {
			if ( ! $order->is_paid() ) {
				$order->payment_complete( $trade_no );
				$order->add_order_note( __( 'HKTPL: Payment confirmed via API query (TRADE_FINISHED).', 'hktpl-gateway' ) );
			}
		} elseif ( in_array( $trade_status, array( 'TRADE_CLOSED', 'TRADE_CANCELLED' ), true ) ) {
			$order->update_status( 'failed', __( 'HKTPL: Payment cancelled or failed (API query).', 'hktpl-gateway' ) );
		}

		if ( $trade_no ) {
			$order->update_meta_data( '_hktpl_trade_no', $trade_no );
		}
		$order->save();
	}

	/**
	 * Process refund via HKTPL API.
	 *
	 * Note: HKTPL v1.0.62 does not expose a public refund endpoint.
	 * Merchants typically process refunds via HKT Merchant Portal.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return new WP_Error( 'error', __( 'Order not found.', 'hktpl-gateway' ) );
		}

		$trade_no = $order->get_meta( '_hktpl_trade_no' );
		if ( ! $trade_no ) {
			return new WP_Error( 'no_trade_no', __( 'No Tap & Go trade number found. Process refund via HKT Merchant Portal.', 'hktpl-gateway' ) );
		}

		return new WP_Error( 'refund_not_supported', __( 'Please process refunds via the HKT Merchant Portal. Trade No: ', 'hktpl-gateway' ) . $trade_no );
	}
}
