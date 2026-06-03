<?php
/**
 * Theme Functions
 * @package NeonLightHK
 */

// Theme setup
add_action( 'after_setup_theme', function() {
	// Title tag support
	add_theme_support( 'title-tag' );

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );

	// HTML5 markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// WooCommerce support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Register menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'neonlighthk' ),
		'footer'  => __( 'Footer Menu', 'neonlighthk' ),
	) );
} );

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
	// Theme stylesheet
	wp_enqueue_style(
		'neonlighthk-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// Google Fonts
	wp_enqueue_style(
		'neonlighthk-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Noto+Sans+TC:wght@300;400;500;700&display=swap',
		array(),
		null
	);
} );

// WooCommerce product per page
add_filter( 'loop_shop_per_page', function( $cols ) {
	return 12;
}, 20 );

// Remove default WooCommerce CSS
add_filter( 'woocommerce_enqueue_styles', function( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );
	return $enqueue_styles;
} );

// WooCommerce body class
add_filter( 'body_class', function( $classes ) {
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'nl-woocommerce';
	}
	return $classes;
} );
