<?php
/**
 * Multilingual Support — Polylang Integration
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// ── Register Strings for Polylang ───────────────────────────
add_action( 'init', 'nl_pll_register_strings' );
function nl_pll_register_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) return;

	// Contact info
	pll_register_string( 'nl_phone',    get_theme_mod( 'nl_phone',    '61319328' ),                                 'neonlighthk', true );
	pll_register_string( 'nl_email',    get_theme_mod( 'nl_email',    'www.neonlight.pro@gmail.com' ),                           'neonlighthk', true );
	pll_register_string( 'nl_address',  get_theme_mod( 'nl_address',  'U/F,Central Pier 8,Hong Kong · 香港中環8號碼頭U層' ), 'neonlighthk', true );

	// Navigation labels
	pll_register_string( 'nav_shop',      '現貨·SHOP',          'neonlighthk' );
	pll_register_string( 'nav_rent',      '租借·RENT',          'neonlighthk' );
	pll_register_string( 'nav_order',     '訂製·ORDER',          'neonlighthk' );
	pll_register_string( 'nav_workshop',  '工作坊·WORKSHOP',     'neonlighthk' );
	pll_register_string( 'nav_projects',  '活動·PROJECTS',      'neonlighthk' );
	pll_register_string( 'nav_lookbook',  '範例·LOOKBOOK',      'neonlighthk' );

	// Section titles
	pll_register_string( 'hero_title_zh', '霓虹燈設計及製作',    'neonlighthk' );
	pll_register_string( 'hero_title_en', 'NEON SIGNS',         'neonlighthk' );
	pll_register_string( 'hero_subtitle', 'DESIGN & PRODUCTION', 'neonlighthk' );
	pll_register_string( 'hero_cta',      'LEARN MORE',         'neonlighthk' );

	pll_register_string( 'card_purchase', '購買現貨',           'neonlighthk' );
	pll_register_string( 'card_purchase_en', 'PURCHASE',        'neonlighthk' );
	pll_register_string( 'card_customise', '訂製設計',          'neonlighthk' );
	pll_register_string( 'card_customise_en', 'CUSTOMISE',     'neonlighthk' );
	pll_register_string( 'card_workshop', '工作坊',             'neonlighthk' );
	pll_register_string( 'card_workshop_en', 'WORKSHOP',        'neonlighthk' );
	pll_register_string( 'card_rental', '租借服務',             'neonlighthk' );
	pll_register_string( 'card_rental_en', 'RENTAL',            'neonlighthk' );

	pll_register_string( 'gallery_title_zh', '作品參考',         'neonlighthk' );
	pll_register_string( 'gallery_title_en', 'OUR WORKS',        'neonlighthk' );
	pll_register_string( 'gallery_instagram', 'INSTAGRAM @ NEONLIGHTHK', 'neonlighthk' );
	pll_register_string( 'gallery_more', 'MORE',               'neonlighthk' );

	pll_register_string( 'visit_title', 'VISIT US at PMQ', 'neonlighthk' );
	pll_register_string( 'clients_title_zh', '客戶支持',         'neonlighthk' );
	pll_register_string( 'clients_title_en', 'OUR BELOVED CLIENTS', 'neonlighthk' );
	pll_register_string( 'contact_title', 'CONTACT US',          'neonlighthk' );

	// Footer
	pll_register_string( 'footer_about',  '關於・ABOUT',         'neonlighthk' );
	pll_register_string( 'footer_services', '服務・SERVICES',      'neonlighthk' );
	pll_register_string( 'footer_legal',    '條款・LEGAL',         'neonlighthk' );
	pll_register_string( 'footer_copyright', 'Cheezo Group Limited. All Rights Reserved.', 'neonlighthk' );

	// WooCommerce
	pll_register_string( 'wc_add_to_cart',    '加入購物車',      'neonlighthk' );
	pll_register_string( 'wc_read_more',        '了解更多',        'neonlighthk' );
	pll_register_string( 'wc_checkout',         '結帳',            'neonlighthk' );
	pll_register_string( 'wc_order_received',   '訂單已收到',      'neonlighthk' );
	pll_register_string( 'wc_payment_method',   '付款方式',        'neonlighthk' );
}

// ── Language Switcher in Header ─────────────────────────────
add_action( 'nl_header_actions', 'nl_language_switcher', 5 );
function nl_language_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) ) return;

	$languages = pll_the_languages( [
		'dropdown'   => 0,
		'echo'       => 0,
		'hide_if_no_translation' => 0,
		'show_flags' => 0,
		'show_names' => 1,
	] );

	if ( empty( $languages ) ) return;

	echo '<div class="nl-lang-switcher">';
	echo '<ul>';
	foreach ( $languages as $lang ) {
		$active = $lang['current_lang'] ? ' class="active"' : '';
		echo '<li' . $active . '><a href="' . esc_url( $lang['url'] ) . '" hreflang="' . esc_attr( $lang['locale'] ) . '">' . esc_html( $lang['name'] ) . '</a></li>';
	}
	echo '</ul>';
	echo '</div>';
}

// ── Translate URLs for Menus ────────────────────────────────
add_filter( 'wp_nav_menu_objects', 'nl_pll_menu_urls', 10, 2 );
function nl_pll_menu_urls( $items, $args ) {
	if ( ! function_exists( 'pll_get_post' ) ) return $items;

	foreach ( $items as $item ) {
		if ( 'page' === $item->type ) {
			$translated_id = pll_get_post( $item->object_id );
			if ( $translated_id ) {
				$item->url = get_permalink( $translated_id );
			}
		}
	}
	return $items;
}

// ── Custom Language Config ──────────────────────────────────
add_action( 'init', 'nl_pll_languages', 1 );
function nl_pll_languages() {
	if ( ! function_exists( 'pll_register_string' ) ) return;

	// Ensure Polylang knows about our custom post types
	add_filter( 'pll_get_post_types', function( $types, $hide ) {
		$types['nl_workshop']      = 'nl_workshop';
		$types['nl_rental']        = 'nl_rental';
		$types['nl_custom_order']  = 'nl_custom_order';
		$types['nl_project']       = 'nl_project';
		$types['nl_lookbook']      = 'nl_lookbook';
		return $types;
	}, 10, 2 );

	// WooCommerce product categories
	add_filter( 'pll_get_taxonomies', function( $tax, $hide ) {
		$tax['product_cat'] = 'product_cat';
		$tax['product_tag'] = 'product_tag';
		return $tax;
	}, 10, 2 );
}
