<?php
/**
 * Theme Functions
 * @package NeonLightHK
 */

// Load translations (must be global)
require_once get_template_directory() . '/inc/translations.php';

// Force custom templates with high priority to override WooCommerce
add_filter('template_include', function($template) {
    if (is_page(13)) {
        $about_template = get_template_directory() . '/page-about.php';
        if (file_exists($about_template)) return $about_template;
    }
    if (is_page(31)) {
        $projects_template = get_template_directory() . '/page-projects.php';
        if (file_exists($projects_template)) return $projects_template;
    }
    if (is_page(4)) {
        $products_template = get_template_directory() . '/page-products.php';
        if (file_exists($products_template)) return $products_template;
    }
    if (is_page(171) || is_page('neon-products')) {
        $neon_products_template = get_template_directory() . '/page-neon-products.php';
        if (file_exists($neon_products_template)) return $neon_products_template;
    }
    return $template;
}, 999);

// Disable canonical redirect for plain page_id permalinks so pages load correctly
remove_filter('template_redirect', 'redirect_canonical');

// Fix site URL to match the request host (localhost vs WAN IP)
add_filter('option_home', 'nl_dynamic_home_url', 99);
add_filter('option_siteurl', 'nl_dynamic_home_url', 99);
function nl_dynamic_home_url($url) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        // HTTP_HOST may already include the port — only add port if it doesn't
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_url = $proto . '://' . $host . $port;
        // Only replace the domain part, preserve the path
        return preg_replace('#^https?://[^/]+#', $new_url, $url);
    }
    return $url;
}

// Also fix stylesheet URI
add_filter('stylesheet_uri', function($uri) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_base = $proto . '://' . $host . $port;
        return preg_replace('#^https?://[^/]+#', $new_base, $uri);
    }
    return $uri;
}, 99);

// Fix template directory URI
add_filter('template_directory_uri', function($uri) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_base = $proto . '://' . $host . $port;
        return preg_replace('#^https?://[^/]+#', $new_base, $uri);
    }
    return $uri;
}, 99);

// Fix any content URLs in the page output (images, links, etc)
add_action('template_redirect', function() {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost' && !is_admin()) {
        ob_start(function($buffer) {
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $has_port = strpos($host, ':') !== false;
            $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
            $new_base = $proto . '://' . $host . $port;
			// Replace localhost:8080 with actual host — preserve original quote char
			$buffer = preg_replace('#(href|src)=(["\'])https?://localhost:8080/#', '$1=$2' . $new_base . '/', $buffer);
			$buffer = preg_replace('#(href|src)=(["\'])https?://localhost/#', '$1=$2' . $new_base . '/', $buffer);
            return $buffer;
        });
    }
});

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
// ===== BOOKING CUSTOM POST TYPE =====
add_action('init', function() {
	register_post_type('nl_booking', [
		'label'              => 'Workshop Bookings',
		'labels'             => [
			'name'          => 'Workshop Bookings',
			'singular_name' => 'Booking',
			'all_items'     => 'All Bookings',
			'edit_item'     => 'View Booking',
			'view_item'     => 'View Booking',
		],
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => ['title'],
		'menu_icon'          => 'dashicons-calendar-alt',
	]);
});

// ===== BOOKING ADMIN COLUMNS =====
add_filter('manage_nl_booking_posts_columns', function($columns) {
	unset($columns['date']);
	$columns['phone']       = 'Phone';
	$columns['themes']      = 'Themes';
	$columns['best_time']   = 'Time Slot';
	$columns['group_prefs'] = 'Group';
	$columns['status']      = 'Status';
	$columns['date']        = 'Date';
	return $columns;
});

add_action('manage_nl_booking_posts_custom_column', function($column, $post_id) {
	switch ($column) {
		case 'phone':
			echo esc_html(get_post_meta($post_id, '_nl_phone', true));
			break;
		case 'themes':
			echo esc_html(get_post_meta($post_id, '_nl_themes', true));
			break;
		case 'best_time':
			echo esc_html(get_post_meta($post_id, '_nl_best_time', true));
			break;
		case 'group_prefs':
			echo esc_html(get_post_meta($post_id, '_nl_group_prefs', true));
			break;
		case 'status':
			echo esc_html(get_post_meta($post_id, '_nl_booking_status', true) ?: get_post_meta($post_id, '_nl_status', true) ?: get_post_status($post_id));
			break;
	}
}, 10, 2);

// ===== BOOKING META BOX =====
add_action('add_meta_boxes', function() {
	add_meta_box(
		'nl_booking_details',
		'Booking Details',
		function($post) {
			$fields = [
				'First Name'  => '_nl_first_name',
				'Last Name'   => '_nl_last_name',
				'Email'       => '_nl_email',
				'Phone'       => '_nl_phone',
				'Age Range'   => '_nl_age_range',
				'Themes'      => '_nl_themes',
				'Best Time'   => '_nl_best_time',
				'Group Prefs' => '_nl_group_prefs',
				'Workshop'    => '_nl_workshop_title',
				'Price'       => '_nl_price',
				'Location'    => '_nl_location',
				'Booking Date'=> '_nl_booking_date',
				'Booking Time'=> '_nl_booking_time',
				'Group Size'  => '_nl_group_size',
				'Remarks'     => '_nl_remarks',
			];
			echo '<table class="form-table">';
			foreach ($fields as $label => $key) {
				$value = get_post_meta($post->ID, $key, true);
				if ($value === '') continue;
				echo '<tr><th>' . esc_html($label) . '</th><td>' . esc_html($value) . '</td></tr>';
			}
			echo '</table>';
		},
		'nl_booking',
		'normal',
		'high'
	);
});

// ===== WORKSHOP PRODUCT HELPER =====
function nl_get_or_create_workshop_product($workshop_id, $title, $price) {
	$existing = get_posts([
		'post_type'      => 'product',
		'meta_key'       => '_nl_workshop_product_id',
		'meta_value'     => $workshop_id,
		'posts_per_page' => 1,
		'post_status'    => 'any',
	]);
	if (!empty($existing)) {
		return $existing[0]->ID;
	}
	$product = new WC_Product_Simple();
	$product->set_name($title);
	$product->set_regular_price($price);
	$product->set_price($price);
	$product->set_status('publish');
	$product->set_catalog_visibility('hidden');
	$product->set_virtual(true);
	$product->set_sold_individually(false);
	$product->update_meta_data('_nl_workshop_product_id', $workshop_id);
	return $product->save();
}


// ===== ABOUT PAGE LANG SWITCHING =====
add_action('wp_head', function() {
    if (!is_page()) return;
    $page = get_post();
    if (!$page || $page->post_name !== 'about') return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var lang = 'en';
        var m = location.search.match(/[?&]lang=(zh|cn|en)/);
        if (m) lang = m[1];
        var isZh = lang === 'zh' || lang === 'cn';
        // Show/hide EN content
        document.querySelectorAll('.nl-intro-en, .nl-heading-en, .nl-programs-en, .nl-desc-en, .nl-upcycle-en, .nl-cta-en').forEach(function(el) {
            el.style.display = isZh ? 'none' : '';
        });
        // Show/hide ZH content
        document.querySelectorAll('.nl-intro-zh, .nl-heading-zh, .nl-programs-zh, .nl-desc-zh, .nl-upcycle-zh, .nl-cta-zh').forEach(function(el) {
            el.style.display = lang === 'zh' ? '' : 'none';
        });
        // Show/hide CN content
        document.querySelectorAll('.nl-intro-cn, .nl-heading-cn, .nl-programs-cn, .nl-desc-cn, .nl-upcycle-cn, .nl-cta-cn').forEach(function(el) {
            el.style.display = lang === 'cn' ? '' : 'none';
        });
    });
    </script>
    <?php
});

// ===== DOCUMENT TITLE TRANSLATION =====
add_filter('document_title_parts', 'nl_translate_document_title');
function nl_translate_document_title($parts) {
    $lang = nl_lang();
    if (is_shop() || is_page_template('page-shop.php')) {
        $parts['title'] = nl_t('shop_title');
    }
    if (is_page_template('page-rental.php')) {
        $parts['title'] = nl_t('balloon_title');
    }
    if (is_product()) {
        $parts['title'] = get_the_title() . ' – ' . nl_t('shop_title');
    }
    return $parts;
}
