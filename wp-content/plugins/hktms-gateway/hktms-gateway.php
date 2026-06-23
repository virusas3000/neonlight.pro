<?php
/**
 * Plugin Name: HKTMS ePayment Gateway
 * Description: WooCommerce payment gateway for HKTMS ePayment (Visa, Mastercard, AlipayHK, AlipayCN, WeChat Pay, Apple Pay). Verified against HKTMS v1.10 spec.
 * Version: 1.10.0
 * Author: Neonlight.pro
 * Text Domain: hktms-gateway
 * Domain Path: /languages
 * WC requires at least: 7.0
 * WC tested up to: 9.8
 *
 * Merchant Onboarding (how to get credentials):
 * 1. Apply at https://www.hktmerchantservices.com/online-payment-gateway.html
 * 2. Submit: Business Registration, Bank Statement, Director ID, Website URL
 * 3. HKT issues: App ID (Payment Method ID) + App Secret (API Key) per payment method
 * 4. One App ID per payment method: Visa/MC, AlipayHK, AlipayCN, WeChatPay
 * 5. Configure notification URL in HKT Merchant Portal and whitelist your domain
 * 6. For Apple Pay: additional Apple Developer verification required
 *
 * @package HKTMS_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'HKTMS_PLUGIN_VERSION', '1.10.0' );
define( 'HKTMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HKTMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Register webhook endpoint before WooCommerce loads.
add_action( 'woocommerce_api_hktms-webhook', 'hktms_webhook_handler' );
add_action( 'woocommerce_api_hktms-return', 'hktms_return_handler' );

function hktms_webhook_handler(): void {
	require_once HKTMS_PLUGIN_DIR . 'includes/class-webhook.php';
	HKTMS_Webhook::handle();
}

function hktms_return_handler(): void {
	require_once HKTMS_PLUGIN_DIR . 'includes/class-gateway.php';
	require_once HKTMS_PLUGIN_DIR . 'includes/class-api.php';
	$gateways = WC()->payment_gateways()->payment_gateways();
	if ( isset( $gateways['hktms'] ) ) {
		$gateways['hktms']->handle_return();
	}
}

// Boot gateway when WooCommerce payment gateways are loaded.
add_filter( 'woocommerce_payment_gateways', 'hktms_add_gateway_class' );
add_action( 'plugins_loaded', 'hktms_init_gateway' );

function hktms_init_gateway(): void {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}
	require_once HKTMS_PLUGIN_DIR . 'includes/class-gateway.php';
	require_once HKTMS_PLUGIN_DIR . 'includes/class-api.php';
	require_once HKTMS_PLUGIN_DIR . 'includes/class-webhook.php';
}

add_action( 'init', function() {
	load_plugin_textdomain( 'hktms-gateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
} );

function hktms_add_gateway_class( array $gateways ): array {
	$gateways[] = 'HKTMS_Gateway';
	return $gateways;
}

// Declare HPOS compatibility.
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
