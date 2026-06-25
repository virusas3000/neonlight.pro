<?php
/**
 * Plugin Name: HKTPL Payment Gateway
 * Description: WooCommerce payment gateway for HKTPL (Tap & Go / FPS). Verified against HKTPL v1.0.62 spec.
 * Version: 1.0.62
 * Author: Neonlight.pro
 * Text Domain: hktpl-gateway
 * Domain Path: /languages
 * WC requires at least: 7.0
 * WC tested up to: 9.8
 *
 * Merchant Onboarding (how to get credentials):
 * 1. Apply at https://www.tapngo.com.hk/merchants or contact HKT Merchant Services
 * 2. Submit: Business Registration, Bank Statement, Director ID, Website URL
 * 3. HKT issues per app: App ID, API Key (for HMAC signing), RSA Public Key (4096-bit)
 * 4. Merchant generates RSA private key pair and submits public key to HKT
 * 5. Configure IP whitelist and callback URLs in HKT Merchant Portal
 * 6. For FPS: additional FPS merchant registration with HKICL required
 *
 * @package HKTPL_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'HKTPL_GATEWAY_VERSION', '1.0.62' );
define( 'HKTPL_GATEWAY_BUILD', 'sigfix-2026-06-25' );
define( 'HKTPL_GATEWAY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HKTPL_GATEWAY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'HKTPL_UAT_BASE_URL', 'https://gateway.sandbox.tapngo.com.hk' );
define( 'HKTPL_PROD_BASE_URL', 'https://gateway.tapngo.com.hk' );

// Autoloader
spl_autoload_register( function ( $class ) {
	$prefix = 'HKTPL_';
	$base_dir = HKTPL_GATEWAY_PLUGIN_DIR . 'includes/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = strtolower( substr( $class, $len ) );
	$file = $base_dir . 'class-' . str_replace( '_', '-', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

// Activation hook
register_activation_hook( __FILE__, 'hktpl_gateway_activate' );
function hktpl_gateway_activate() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die(
			esc_html__( 'HKTPL Payment Gateway requires WooCommerce to be installed and activated.', 'hktpl-gateway' ),
			esc_html__( 'Plugin Activation Error', 'hktpl-gateway' ),
			array( 'back_link' => true )
		);
	}
}

// Init gateway and endpoints
add_action( 'plugins_loaded', 'hktpl_gateway_init', 11 );
function hktpl_gateway_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	require_once HKTPL_GATEWAY_PLUGIN_DIR . 'includes/class-gateway.php';
	require_once HKTPL_GATEWAY_PLUGIN_DIR . 'includes/class-api.php';
	require_once HKTPL_GATEWAY_PLUGIN_DIR . 'includes/class-crypto.php';
	require_once HKTPL_GATEWAY_PLUGIN_DIR . 'includes/class-webhook.php';

	add_filter( 'woocommerce_payment_gateways', 'hktpl_add_gateway' );
	add_action( 'woocommerce_api_hktpl-webhook', 'hktpl_handle_webhook' );
	add_action( 'woocommerce_api_hktpl-return', 'hktpl_handle_return' );
	add_action( 'woocommerce_api_hktpl-check', 'hktpl_handle_check' );
}

function hktpl_add_gateway( $gateways ) {
	$gateways[] = 'HKTPL_Gateway';
	return $gateways;
}

function hktpl_handle_webhook() {
	HKTPL_Webhook::handle();
}

function hktpl_handle_return() {
	$gateways = WC()->payment_gateways()->payment_gateways();
	if ( isset( $gateways['hktpl'] ) ) {
		$gateways['hktpl']->handle_return();
	}
}

function hktpl_handle_check() {
	$gateways = WC()->payment_gateways()->payment_gateways();
	if ( isset( $gateways['hktpl'] ) ) {
		$gateways['hktpl']->check_payment_status();
	}
}

// Declare HPOS compatibility
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
