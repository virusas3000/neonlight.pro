<?php
/**
 * HKTMS WooCommerce Payment Gateway — Verified against HKTMS ePayment Gateway v1.10
 *
 * Handles Visa/Mastercard, AlipayHK, AlipayCN, WeChatPay, Apple Pay via HKTMS.
 *
 * @package HKTMS_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTMS_Gateway extends WC_Payment_Gateway {

	public $testmode;
	public $api_app_id;
	public $api_app_secret;
	public $notification_url;
	public $success_url;
	public $fail_url;
	public $language;
	public $auto_capture;

	public function __construct() {
		$this->id                 = 'hktms';
		$this->icon               = HKTMS_PLUGIN_URL . 'assets/images/hktms-logo.png';
		$this->has_fields         = true;
		$this->method_title       = __( 'HKTMS (Visa / Mastercard / Alipay / WeChat / Apple Pay)', 'hktms-gateway' );
		$this->method_description = __( 'Accept payments via HKTMS ePayment Gateway. Supports Visa, Mastercard, AlipayHK, AlipayCN, WeChat Pay and Apple Pay.', 'hktms-gateway' );
		$this->supports           = [ 'products', 'refunds', 'tokenization' ];

		$this->init_form_fields();
		$this->init_settings();

		$this->title            = $this->get_option( 'title', __( 'Credit Card / Wallet Payment', 'hktms-gateway' ) );
		$this->description      = $this->get_option( 'description' );
		$this->enabled           = $this->get_option( 'enabled', 'no' );
		$this->testmode          = $this->get_option( 'testmode', 'yes' );
		$this->api_app_id       = $this->get_option( 'api_app_id' );
		$this->api_app_secret    = $this->get_option( 'api_app_secret' );
		$this->notification_url  = $this->get_option( 'notification_url' );
		$this->success_url       = $this->get_option( 'success_url', wc_get_checkout_url() . '?order-received=' );
		$this->fail_url          = $this->get_option( 'fail_url', wc_get_checkout_url() );
		$this->language          = $this->get_option( 'language', 'en_US' );
		$this->auto_capture      = $this->get_option( 'auto_capture', 'yes' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		add_action( 'woocommerce_receipt_' . $this->id, [ $this, 'receipt_page' ] );
		add_action( 'woocommerce_api_hktms-return', [ $this, 'handle_return' ] );
	}

	// ── Admin Settings ────────────────────────────────────────
	public function init_form_fields() {
		$this->form_fields = [
			'enabled' => [
				'title'   => __( 'Enable/Disable', 'hktms-gateway' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable HKTMS Payment Gateway', 'hktms-gateway' ),
				'default' => 'yes',
			],
			'testmode' => [
				'title'       => __( 'Test Mode', 'hktms-gateway' ),
				'type'        => 'checkbox',
				'label'       => __( 'Use UAT (Sandbox) Environment', 'hktms-gateway' ),
				'description' => __( 'When checked, transactions use the HKTMS UAT environment.', 'hktms-gateway' ),
				'default'     => 'yes',
			],
			'title' => [
				'title'       => __( 'Title', 'hktms-gateway' ),
				'type'        => 'text',
				'description' => __( 'Payment method title shown to customers at checkout.', 'hktms-gateway' ),
				'default'     => __( 'Credit Card / Wallet Payment', 'hktms-gateway' ),
				'desc_tip'    => true,
			],
			'description' => [
				'title'       => __( 'Description', 'hktms-gateway' ),
				'type'        => 'textarea',
				'description' => __( 'Additional info shown under the payment method.', 'hktms-gateway' ),
				'default'     => __( 'Pay securely with Visa, Mastercard, AlipayHK, AlipayCN, WeChat Pay or Apple Pay via HKTMS.', 'hktms-gateway' ),
			],
			'api_app_id' => [
				'title'       => __( 'App ID (Payment Method ID)', 'hktms-gateway' ),
				'type'        => 'text',
				'description' => __( 'Your HKTMS App ID. One per payment method (Visa/MC, AlipayHK, AlipayCN, WeChatPay each have separate App IDs). Use the one for your primary payment method here.', 'hktms-gateway' ),
				'desc_tip'    => true,
			],
			'api_app_secret' => [
				'title'       => __( 'App Secret (API Key)', 'hktms-gateway' ),
				'type'        => 'password',
				'description' => __( 'Your HKTMS API Key (App Secret). Base64-encoded 512-bit secret provided by HKT. Keep this confidential.', 'hktms-gateway' ),
				'desc_tip'    => true,
			],
			'language' => [
				'title'       => __( 'Payment Page Language', 'hktms-gateway' ),
				'type'        => 'select',
				'description' => __( 'Language for the HKTMS hosted payment page (Visa/Mastercard only).', 'hktms-gateway' ),
				'options'     => [
					'en_US' => 'English (US)',
					'zh_CN' => '简体中文',
					'zh_TW' => '繁體中文',
					'en_GB' => 'English (UK)',
					'ja_JP' => '日本語',
				],
				'default'     => 'en_US',
				'desc_tip'    => true,
			],
			'notification_url' => [
				'title'       => __( 'Notification URL', 'hktms-gateway' ),
				'type'        => 'url',
				'description' => __( 'URL to receive async order status callbacks (webhooks). Must be whitelisted with HKT. Auto-generated — copy this to HKT Merchant Portal.', 'hktms-gateway' ),
				'default'     => site_url( '/wc-api/hktms-webhook/' ),
				'desc_tip'    => true,
				'custom_attributes' => [ 'readonly' => 'readonly' ],
			],
			'success_url' => [
				'title'       => __( 'Success URL', 'hktms-gateway' ),
				'type'        => 'url',
				'description' => __( 'Redirect URL after successful payment. Prefer webhook/API for status confirmation.', 'hktms-gateway' ),
				'default'     => wc_get_endpoint_url( 'order-received', '', wc_get_checkout_url() ),
				'desc_tip'    => true,
			],
			'fail_url' => [
				'title'       => __( 'Fail URL', 'hktms-gateway' ),
				'type'        => 'url',
				'description' => __( 'Redirect URL after declined/failed payment. Prefer webhook/API for status confirmation.', 'hktms-gateway' ),
				'default'     => wc_get_checkout_url(),
				'desc_tip'    => true,
			],
			'auto_capture' => [
				'title'       => __( 'Auto Capture', 'hktms-gateway' ),
				'type'        => 'checkbox',
				'label'       => __( 'Automatically mark orders as complete on CAPTURED/COMPLETED status', 'hktms-gateway' ),
				'default'     => 'yes',
			],
		];
	}

	// ── Payment Fields at Checkout ────────────────────────────
	public function payment_fields() {
		if ( $this->description ) {
			echo wpautop( esc_html( $this->description ) );
		}

		$methods = [
			'visamastercard' => __( 'Visa / Mastercard', 'hktms-gateway' ),
			'applepay'       => __( 'Apple Pay', 'hktms-gateway' ),
			'alipayhk'       => __( 'AlipayHK', 'hktms-gateway' ),
			'alipaycn'       => __( 'Alipay (Mainland)', 'hktms-gateway' ),
			'wechatpay'      => __( 'WeChat Pay', 'hktms-gateway' ),
		];
		?>
		<fieldset id="hktms-payment-method-select">
			<p class="form-row"><?php esc_html_e( 'Select a payment method:', 'hktms-gateway' ); ?></p>
			<div class="hktms-method-list">
			<?php foreach ( $methods as $key => $label ) :
				$img_path = HKTMS_PLUGIN_DIR . 'assets/images/' . $key . '.png';
				$img_url  = HKTMS_PLUGIN_URL . 'assets/images/' . $key . '.png';
				$first    = ( $key === 'visamastercard' );
			?>
				<label class="hktms-method-label<?php echo $first ? ' hktms-method-selected' : ''; ?>">
					<input type="radio" name="hktms_payment_method" value="<?php echo esc_attr( $key ); ?>" <?php checked( $first ); ?>>
					<span class="hktms-method-text"><?php echo esc_html( $label ); ?></span>
					<?php if ( file_exists( $img_path ) ) : ?>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $label ); ?>">
					<?php endif; ?>
				</label>
			<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Build Create Payment URL payload per HKTMS v1.10 spec.
	 *
	 * Mandatory fields: currency, chargeTotal, merchantTransactionId, responseFailUrl, responseSuccessUrl, customerEmail
	 * Optional fields: customerId, notificationUrl, invoiceNumber, poNumber, language, tokenId, appId
	 */
	private function build_payload( WC_Order $order, string $method ): array {
		$payload = [
			'currency'              => $order->get_currency(),
			'chargeTotal'           => (float) $order->get_total(),
			'merchantTransactionId' => (string) $order->get_id(),
			'customerEmail'         => $order->get_billing_email(),
			'responseFailUrl'       => add_query_arg( [
				'order_id' => $order->get_id(),
				'key'      => $order->get_order_key(),
				'status'   => 'failed',
			], $this->fail_url ),
			'responseSuccessUrl'    => add_query_arg( [
				'order_id' => $order->get_id(),
				'key'      => $order->get_order_key(),
				'status'   => 'success',
			], $this->success_url ),
		];

		// Optional fields
		if ( $order->get_customer_id() > 0 ) {
			$payload['customerId'] = (string) $order->get_customer_id();
		}

		if ( $this->notification_url ) {
			$payload['notificationUrl'] = $this->notification_url;
		}

		$invoice_number = $order->get_order_number();
		if ( $invoice_number ) {
			$payload['invoiceNumber'] = substr( (string) $invoice_number, 0, 48 );
		}

		$po_number = $order->get_meta( '_po_number' );
		if ( $po_number ) {
			$payload['poNumber'] = substr( (string) $po_number, 0, 50 );
		}

		// Language — Visa/Mastercard hosted page only
		if ( in_array( $method, [ 'visamastercard', 'applepay' ], true ) && $this->language ) {
			$payload['language'] = $this->language;
		}

		// Token ID for saved cards (Visa/MC only)
		$token_id = $order->get_meta( '_hktms_token_id' );
		if ( $token_id && in_array( $method, [ 'visamastercard', 'applepay' ], true ) ) {
			$payload['tokenId'] = (string) $token_id;
		}

		// WeChatPay requires appId
		if ( 'wechatpay' === $method ) {
			$wechat_app_id = $this->get_option( 'wechat_app_id', '' );
			if ( $wechat_app_id ) {
				$payload['appId'] = $wechat_app_id;
			}
		}

		return $payload;
	}

	// ── Process Payment ───────────────────────────────────────
	public function process_payment( $order_id ) {
		$order  = wc_get_order( $order_id );
		$method = sanitize_text_field( $_POST['hktms_payment_method'] ?? 'visamastercard' );

		// Validate credentials
		if ( empty( $this->api_app_id ) || empty( $this->api_app_secret ) ) {
			wc_add_notice( __( 'HKTMS payment gateway is not configured. Please contact the store admin.', 'hktms-gateway' ), 'error' );
			return [ 'result' => 'failure' ];
		}

		$api     = new HKTMS_API( $this->testmode, $this->api_app_id, $this->api_app_secret );
		$payload = $this->build_payload( $order, $method );
		$response = $api->create_payment_url( $method, $payload );

		if ( is_wp_error( $response ) ) {
			wc_add_notice( $response->get_error_message(), 'error' );
			return [ 'result' => 'failure' ];
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['status'] ) || $body['status'] !== '0' || empty( $body['payload']['paymentUrl'] ) ) {
			$msg = $body['message'] ?? __( 'Failed to create payment. Please try again.', 'hktms-gateway' );
			wc_add_notice( $msg, 'error' );
			return [ 'result' => 'failure' ];
		}

		// Store HKTMS order ID
		$order->update_meta_data( '_hktms_order_id', $body['payload']['orderId'] );
		$order->update_meta_data( '_hktms_payment_method', $method );
		$order->update_meta_data( '_hktms_payment_url', $body['payload']['paymentUrl'] );
		$order->update_meta_data( '_hktms_merchant_txn_id', $body['payload']['merchantTransactionId'] ?? $order->get_id() );
		$order->save_meta_data();

		$order->update_status( 'pending', __( 'Awaiting HKTMS payment completion.', 'hktms-gateway' ) );
		WC()->cart->empty_cart();

		return [
			'result'   => 'success',
			'redirect' => $body['payload']['paymentUrl'],
		];
	}

	// ── Receipt Page (fallback redirect) ──────────────────────
	public function receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );
		$url   = $order->get_meta( '_hktms_payment_url' );
		if ( $url ) {
			echo '<p>' . esc_html__( 'Redirecting to secure payment page...', 'hktms-gateway' ) . '</p>';
			echo '<script>window.location.href = ' . wp_json_encode( esc_url_raw( $url ) ) . ';</script>';
		}
	}

	// ── Handle Return (Customer lands back) ─────────────────
	public function handle_return() {
		$order_id = absint( $_GET['order_id'] ?? 0 );
		$key      = sanitize_text_field( $_GET['key'] ?? '' );
		$order    = wc_get_order( $order_id );

		if ( ! $order || $order->get_order_key() !== $key ) {
			wp_safe_redirect( wc_get_checkout_url() );
			exit;
		}

		$method = $order->get_meta( '_hktms_payment_method' ) ?: 'visamastercard';
		$api    = new HKTMS_API( $this->testmode, $this->api_app_id, $this->api_app_secret );
		$status = $api->fetch_order_status( $method, $order->get_meta( '_hktms_order_id' ), $order->get_id() );

		if ( is_wp_error( $status ) ) {
			wp_safe_redirect( $order->get_checkout_order_received_url() );
			exit;
		}

		$body = json_decode( wp_remote_retrieve_body( $status ), true );
		$this->update_order_from_status( $order, $body, $method );

		wp_safe_redirect( $order->get_checkout_order_received_url() );
		exit;
	}

	/**
	 * Update Order from HKTMS Status Response.
	 *
	 * Transaction states differ by payment method:
	 *   Visa/MC/Apple Pay: TEMPLATE, CAPTURED, DECLINED, FAILED
	 *   Alipay/WeChat:     COMPLETED, FAILED, PENDING
	 */
	public function update_order_from_status( $order, $body, string $method = 'visamastercard' ) {
		if ( empty( $body['payload']['transactions'] ) ) return;

		$transactions = $body['payload']['transactions'];
		$latest       = end( $transactions );
		$state        = strtoupper( $latest['transactionState'] ?? '' );
		$hktms_txn_id = $latest['transactionId'] ?? '';

		if ( $hktms_txn_id ) {
			$order->update_meta_data( '_hktms_transaction_id', $hktms_txn_id );
		}

		// Store card token if present (Visa/MC tokenization)
		if ( in_array( $method, [ 'visamastercard', 'applepay' ], true ) && ! empty( $latest['tokenId'] ) ) {
			$order->update_meta_data( '_hktms_token_id', sanitize_text_field( $latest['tokenId'] ) );
		}

		// Determine success states per method
		$success_states = in_array( $method, [ 'alipayhk', 'alipaycn', 'wechatpay' ], true )
			? [ 'COMPLETED' ]
			: [ 'CAPTURED' ];

		$failure_states = [ 'DECLINED', 'FAILED' ];
		$pending_states = in_array( $method, [ 'alipayhk', 'alipaycn', 'wechatpay' ], true )
			? [ 'PENDING' ]
			: [ 'TEMPLATE' ];

		if ( in_array( $state, $success_states, true ) ) {
			if ( ! $order->is_paid() ) {
				$order->payment_complete( $hktms_txn_id );
				$order->add_order_note( sprintf(
					/* translators: 1: Transaction state, 2: Transaction ID */
					__( 'HKTMS: Payment %1$s (Txn: %2$s).', 'hktms-gateway' ),
					$state,
					$hktms_txn_id ?: __( 'N/A', 'hktms-gateway' )
				) );
			}
		} elseif ( in_array( $state, $failure_states, true ) ) {
			$order->update_status( 'failed', sprintf(
				__( 'HKTMS: Payment %1$s (Txn: %2$s).', 'hktms-gateway' ),
				$state,
				$hktms_txn_id ?: __( 'N/A', 'hktms-gateway' )
			) );
		} elseif ( in_array( $state, $pending_states, true ) ) {
			if ( ! $order->has_status( 'pending' ) ) {
				$order->update_status( 'pending', sprintf(
					__( 'HKTMS: Payment pending (%1$s).', 'hktms-gateway' ),
					$state
				) );
			}
		} else {
			$order->add_order_note( sprintf(
				__( 'HKTMS: Unrecognized state %1$s (Txn: %2$s). No status change.', 'hktms-gateway' ),
				$state,
				$hktms_txn_id ?: __( 'N/A', 'hktms-gateway' )
			) );
		}

		$order->save_meta_data();
	}

	/**
	 * Process Refund via HKTMS API (future enhancement).
	 *
	 * Currently returns manual instruction — HKTMS v1.10 does not expose a public refund endpoint
	 * for all acquirers. Merchants typically process refunds via HKT Merchant Portal.
	 *
	 * @param int    $order_id WooCommerce order ID
	 * @param float  $amount   Refund amount
	 * @param string $reason   Refund reason
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return new WP_Error( 'invalid_order', __( 'Invalid order.', 'hktms-gateway' ) );
		}

		$hktms_txn_id = $order->get_meta( '_hktms_transaction_id' );
		if ( ! $hktms_txn_id ) {
			return new WP_Error( 'no_txn_id', __( 'No HKTMS transaction ID found. Process refund manually via HKT Merchant Portal.', 'hktms-gateway' ) );
		}

		// Future: call HKTMS refund endpoint if available
		return new WP_Error( 'refund_not_supported', __( 'Please process refunds via the HKT Merchant Portal. Transaction ID: ', 'hktms-gateway' ) . $hktms_txn_id );
	}
}
